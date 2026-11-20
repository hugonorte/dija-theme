<?php
/**
 * Formulário de contato: endpoint REST, mensagens no painel e envio por SMTP.
 *
 * Portado do tema anterior (Nuxt) sem mudanças de comportamento:
 * POST /wp-json/dija/v1/contact  {name, email, whatsapp, message, hp_campo_vazio, form_ts}
 * - Proteção contra robôs (honeypot, tempo, limite, bloqueio temporário):
 *   inc/form-security.php.
 * - Cada mensagem vira um item em "Contatos" no painel, com resposta por e-mail.
 */

defined( 'ABSPATH' ) || exit;

// 1. Configuração de SMTP (variáveis do Docker em dev ou constantes no wp-config em produção)
function dija_get_config($key, $default = '') {
    if (defined($key)) return constant($key);
    $env = getenv($key);
    return ($env !== false) ? $env : $default;
}

add_action( 'phpmailer_init', function( $phpmailer ) {
    // Sem servidor configurado, mantém o envio padrão do WordPress.
    if ( ! dija_get_config('SMTP_HOST') ) return;
    $phpmailer->isSMTP();
    $phpmailer->Host       = dija_get_config('SMTP_HOST', 'smtp.hostinger.com');
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Port       = dija_get_config('SMTP_PORT', 465);
    $phpmailer->Username   = dija_get_config('SMTP_USER', '');
    $phpmailer->Password   = dija_get_config('SMTP_PASS', '');
    $phpmailer->SMTPSecure = (dija_get_config('SMTP_PORT') == '465') ? 'ssl' : 'tls';
    $phpmailer->From       = dija_get_config('SMTP_FROM', dija_get_config('SMTP_USER'));
    $phpmailer->FromName   = dija_get_config('SMTP_NAME', 'DIJA - Blog');
} );

add_filter( 'wp_mail_from', function( $email ) {
    return dija_get_config('SMTP_FROM', dija_get_config('SMTP_USER')) ?: $email;
} );

add_filter( 'wp_mail_from_name', function( $name ) {
    return dija_get_config('SMTP_NAME', 'DIJA - Blog');
} );

// 2. Registro do Custom Post Type para Mensagens de Contato
function dija_register_contact_cpt() {
    $labels = array(
        'name'               => 'Mensagens de Contato',
        'singular_name'      => 'Mensagem',
        'menu_name'          => 'Contatos',
        'add_new'            => 'Nova Mensagem',
        'edit_item'          => 'Ver Mensagem',
        'all_items'          => 'Todas as Mensagens',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'capability_type'    => 'post',
        'hierarchical'       => false,
        'supports'           => array( 'title', 'editor' ),
        'menu_icon'          => 'dashicons-email-alt',
    );

    register_post_type( 'contact_message', $args );
}
add_action( 'init', 'dija_register_contact_cpt' );

/**
 * 3. Interface Administrativa: Colunas e Meta Boxes
 */
add_filter( 'manage_contact_message_posts_columns', function( $columns ) {
    $columns['contact_email'] = 'E-mail';
    $columns['contact_whatsapp'] = 'WhatsApp';
    return $columns;
} );

add_action( 'manage_contact_message_posts_custom_column', function( $column, $post_id ) {
    if ( $column === 'contact_email' ) {
        $email = get_post_meta( $post_id, '_contact_email', true );
        echo $email ? '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>' : '—';
    }
    if ( $column === 'contact_whatsapp' ) {
        $wa = get_post_meta( $post_id, '_contact_whatsapp', true );
        if ( $wa ) {
            $clean_wa = preg_replace( '/[^0-9]/', '', $wa );
            echo '<a href="https://wa.me/55' . esc_attr( $clean_wa ) . '" target="_blank">' . esc_html( $wa ) . '</a>';
        } else {
            echo '—';
        }
    }
}, 10, 2 );

add_action( 'add_meta_boxes', function() {
    add_meta_box( 'dija_contact_details', 'Responder Mensagem', 'dija_render_contact_meta_box', 'contact_message', 'side', 'high' );
} );

function dija_render_contact_meta_box( $post ) {
    $email = get_post_meta( $post->ID, '_contact_email', true );
    $wa    = get_post_meta( $post->ID, '_contact_whatsapp', true );
    $replied = get_post_meta( $post->ID, '_replied_at', true );

    echo '<div id="dija-reply-status">' . ($replied ? '<div class="notice notice-success inline"><p>✅ Respondido em: ' . esc_html( $replied ) . '</p></div>' : '') . '</div>';

    if ( $email ) {
        ?>
        <div class="dija-reply-form">
            <p><strong>Para:</strong> <?php echo esc_html($email); ?></p>
            <input type="text" id="dija-reply-subject" placeholder="Assunto" style="width:100%; margin-bottom:10px;" value="Re: Contato DIJA">
            <textarea id="dija-reply-message" placeholder="Escreva aqui..." style="width:100%; height:120px; margin-bottom:10px;"></textarea>
            <button type="button" id="dija-send-btn" class="button button-primary" style="width:100%;">🚀 Enviar Agora</button>
        </div>
        <script>
        jQuery(document).ready(function($) {
            $('#dija-send-btn').on('click', function() {
                var btn = $(this);
                var data = {
                    action: 'dija_send_reply_ajax',
                    post_id: <?php echo $post->ID; ?>,
                    email: '<?php echo esc_js($email); ?>',
                    subject: $('#dija-reply-subject').val(),
                    message: $('#dija-reply-message').val(),
                    nonce: '<?php echo wp_create_nonce("dija_reply_nonce"); ?>'
                };

                btn.prop('disabled', true).text('Enviando...');
                
                $.post(ajaxurl, data, function(response) {
                    if(response.success) {
                        $('#dija-reply-status').html('<div class="notice notice-success inline"><p>✅ ' + response.data + '</p></div>');
                        $('.dija-reply-form').hide();
                    } else {
                        alert('Erro: ' + response.data);
                        btn.prop('disabled', false).text('Tentar Novamente');
                    }
                });
            });
        });
        </script>
        <?php
    }

    if ( $wa ) {
        $clean_wa = preg_replace( '/[^0-9]/', '', $wa );
        echo '<hr><p><strong>WhatsApp:</strong> ' . esc_html( $wa ) . '</p>';
        echo '<a href="https://wa.me/55' . esc_attr( $clean_wa ) . '" target="_blank" class="button" style="width:100%; text-align:center; background-color:#25d366; color:white;">💬 WhatsApp</a>';
    }
}

/**
 * 4. Handler AJAX para Envio de E-mail
 */
add_action( 'wp_ajax_dija_send_reply_ajax', function() {
    check_ajax_referer( 'dija_reply_nonce', 'nonce' );

    $post_id = intval( $_POST['post_id'] );
    $to      = sanitize_email( $_POST['email'] );
    $subject = sanitize_text_field( $_POST['subject'] );
    $message = sanitize_textarea_field( $_POST['message'] );

    // Depuração Profunda
    $smtp_host = dija_get_config('SMTP_HOST');
    $smtp_user = dija_get_config('SMTP_USER');
    $smtp_pass = dija_get_config('SMTP_PASS') ? '*******' : 'VAZIO';
    
    error_log( "AJAX SMTP: Iniciando envio. Host: $smtp_host | User: $smtp_user | Pass: $smtp_pass" );
    error_log( "AJAX SMTP: Destinatário: $to" );

    try {
        $sent = wp_mail( $to, $subject, $message );
        
        if ( $sent ) {
            $now = current_time( 'd/m/Y H:i' );
            update_post_meta( $post_id, '_replied_at', $now );
            error_log( "AJAX SMTP: Sucesso detectado pelo wp_mail" );
            wp_send_json_success( "E-mail enviado em $now" );
        } else {
            error_log( "AJAX SMTP: wp_mail retornou FALSE" );
            wp_send_json_error( "O servidor de e-mail recusou o envio. Verifique usuário/senha no .env" );
        }
    } catch (Exception $e) {
        error_log( "AJAX SMTP: EXCEÇÃO: " . $e->getMessage() );
        wp_send_json_error( "Erro crítico: " . $e->getMessage() );
    }
} );

/**
 * 5. Capturar erro detalhado do PHPMailer
 */
add_action( 'wp_mail_failed', function( $error ) {
    error_log( 'ERRO DETALHADO SMTP: ' . print_r( $error->get_error_message(), true ) );
} );

// 6. Rota da REST API usada pelo formulário do site (assets/app.js)
add_action( 'rest_api_init', function () {
    register_rest_route( 'dija/v1', '/contact', array(
        'methods'  => 'POST',
        'callback' => 'dija_handle_contact_submission',
        'permission_callback' => '__return_true',
    ) );
} );

function dija_handle_contact_submission( WP_REST_Request $request ) {
    $params   = (array) $request->get_json_params();
    $name     = sanitize_text_field( $params['name'] ?? '' );
    $email    = sanitize_email( $params['email'] ?? '' );
    $whatsapp = sanitize_text_field( $params['whatsapp'] ?? '' );
    $message  = sanitize_textarea_field( $params['message'] ?? '' );
    // Campo-armadilha (honeypot): invisível para pessoas, robôs costumam preencher.
    // "fax_number" é o nome antigo, aceito por compatibilidade.
    $honeypot = trim( (string) ( $params['hp_campo_vazio'] ?? '' ) . (string) ( $params['fax_number'] ?? '' ) );
    $stamp    = (string) ( $params['form_ts'] ?? '' );
    $ip       = dija_get_client_ip();
    // Resposta usada para robôs: parece sucesso, para que não tentem de novo.
    $silent   = new WP_REST_Response( array( 'message' => 'Recebido!' ), 200 );

    // 1. IP bloqueado temporariamente (caiu no honeypot há pouco).
    if ( dija_is_ip_blocked( $ip ) ) {
        error_log( "CONTATO: envio ignorado, IP bloqueado: $ip" );
        return $silent;
    }

    // 2. Honeypot preenchido: robô. Bloqueia o IP (só o formulário, por tempo limitado).
    if ( '' !== $honeypot ) {
        dija_block_ip( $ip, 'honeypot' );
        error_log( "CONTATO: honeypot preenchido; IP $ip bloqueado por " . round( dija_block_ttl() / HOUR_IN_SECONDS ) . ' h.' );
        return $silent;
    }

    // 3. Armadilha de tempo: rápido demais ou sem o carimbo do formulário.
    if ( ! dija_form_timestamp_ok( $stamp ) ) {
        error_log( "CONTATO: envio descartado (enviado rápido demais ou sem carimbo do formulário). IP $ip" );
        return $silent;
    }

    if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
        return new WP_Error( 'missing', 'Campos obrigatórios ausentes.', array( 'status' => 400 ) );
    }

    // 4. Limite de envios por IP (protege a caixa de e-mail contra inundação).
    if ( dija_rate_limited( $ip ) ) {
        return new WP_Error( 'rate_limited', 'Muitas mensagens em pouco tempo. Tente de novo mais tarde ou fale pelo WhatsApp.', array( 'status' => 429 ) );
    }

    $post_id = wp_insert_post( array(
        'post_title'   => 'Mensagem de: ' . $name,
        'post_content' => "De: $name <$email>\nWhatsApp: $whatsapp\n\n" . $message,
        'post_status'  => 'publish',
        'post_type'    => 'contact_message',
    ) );

    update_post_meta( $post_id, '_contact_email', $email );
    update_post_meta( $post_id, '_contact_whatsapp', $whatsapp );

    // Envio legítimo: avisa por e-mail (a mensagem já está salva em "Contatos",
    // então uma falha no e-mail não perde o contato).
    dija_notify_contact_message( (int) $post_id, $name, $email, $whatsapp, $message );

    return new WP_REST_Response( array( 'message' => 'Recebido!' ), 200 );
}

/**
 * Envia a mensagem recebida para o e-mail do Personalizador
 * (padrão: contato@dija.com.br). "Responder" no programa de e-mail vai
 * direto para quem escreveu (Reply-To).
 */
function dija_notify_contact_message( int $post_id, string $name, string $email, string $whatsapp, string $message ): bool {
    $to = sanitize_email( (string) dija_mod( 'contato_email' ) ) ?: 'contato@dija.com.br';

    // Nome sem caracteres que quebrariam o cabeçalho do e-mail.
    $safe_name = trim( preg_replace( '/[\r\n<>",;]+/', ' ', $name ) );
    $subject   = sprintf( '[Site] Nova mensagem de %s', $safe_name );
    $body      = implode( "\n", array(
        'Nova mensagem enviada pelo formulário do site.',
        '',
        'Nome: ' . $name,
        'E-mail: ' . $email,
        'WhatsApp: ' . ( $whatsapp ?: '—' ),
        '',
        'Mensagem:',
        $message,
        '',
        '—',
        'Ver no painel: ' . admin_url( 'post.php?post=' . $post_id . '&action=edit' ),
    ) );
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    if ( is_email( $email ) ) {
        $headers[] = sprintf( 'Reply-To: %s <%s>', $safe_name, $email );
    }

    $sent = wp_mail( $to, $subject, $body, $headers );
    if ( $sent ) {
        update_post_meta( $post_id, '_notified_at', current_time( 'mysql' ) );
    } else {
        error_log( "CONTATO: falha ao enviar o aviso da mensagem $post_id para $to (a mensagem está salva no painel)." );
    }
    return $sent;
}

// Proteções do formulário (IP, honeypot, tempo, limite): inc/form-security.php
