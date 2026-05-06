<?php
/**
 * Tema DIJA - Funções do Backend
 */

// 1. Configuração de SMTP Híbrida (Docker Dev ou WP-Config Prod)
function dija_get_config($key, $default = '') {
    if (defined($key)) return constant($key);
    $env = getenv($key);
    return ($env !== false) ? $env : $default;
}

add_action( 'phpmailer_init', function( $phpmailer ) {
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

// 1.1 Forçar Remetente Correto
add_filter( 'wp_mail_from', function( $email ) {
    return dija_get_config('SMTP_FROM', dija_get_config('SMTP_USER'));
} );

add_filter( 'wp_mail_from_name', function( $name ) {
    return dija_get_config('SMTP_NAME', 'DIJA - Blog');
} );

// 2. Registro do Custom Post Type para Mensagens de Contato
function dija_register_contact_cpt() {
    $labels = array(
        'name'               => 'Mensagens de Contato',
        'singular_name'      => 'Mensagem',
        'menu_name'          => 'Contatos (Nuxt)',
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

// 6. Registro da Rota da REST API (Nuxt)
add_action( 'rest_api_init', function () {
    register_rest_route( 'dija/v1', '/contact', array(
        'methods'  => 'POST',
        'callback' => 'dija_handle_contact_submission',
        'permission_callback' => '__return_true',
    ) );
} );

function dija_handle_contact_submission( WP_REST_Request $request ) {
    $params = $request->get_json_params();
    $name = sanitize_text_field($params['name'] ?? '');
    $email = sanitize_email($params['email'] ?? '');
    $whatsapp = sanitize_text_field($params['whatsapp'] ?? '');
    $message = sanitize_textarea_field($params['message'] ?? '');
    $honeypot = $params['fax_number'] ?? '';
    $ip = dija_get_client_ip();

    // 1. Verificar se o IP já está na lista negra nativa do WP
    if (dija_is_ip_disallowed($ip)) {
        error_log("BLOCK: Tentativa de envio de IP já banido: $ip");
        return new WP_REST_Response(array('message' => 'Recebido!'), 200);
    }

    // 2. Lógica Honeypot: Se preenchido, é robô. Adicionamos o IP à lista negra.
    if (!empty($honeypot)) {
        dija_add_ip_to_disallowed_list($ip);
        error_log("HONEYPOT & BAN: Spam detectado. IP $ip adicionado à lista negra.");
        return new WP_REST_Response(array('message' => 'Recebido!'), 200);
    }

    if (empty($name) || empty($email) || empty($message)) {
        return new WP_Error('missing', 'Campos obrigatórios ausentes.', array('status' => 400));
    }

    $post_id = wp_insert_post(array(
        'post_title' => "Mensagem de: " . $name,
        'post_content' => "De: $name <$email>\nWhatsApp: $whatsapp\n\n" . $message,
        'post_status' => 'publish',
        'post_type' => 'contact_message',
    ));

    update_post_meta($post_id, '_contact_email', $email);
    update_post_meta($post_id, '_contact_whatsapp', $whatsapp);

    return new WP_REST_Response(array('message' => 'Recebido!'), 200);
}

/**
 * Funções Auxiliares de Segurança
 */

// Pega o IP real do cliente
function dija_get_client_ip() {
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $ip;
                }
            }
        }
    }
    return $_SERVER['REMOTE_ADDR'];
}

// Verifica se o IP está na lista de banidos do WordPress (Settings > Discussion)
function dija_is_ip_disallowed($ip) {
    $list = get_option('disallowed_keys');
    if (empty($list)) return false;
    
    $words = explode("\n", $list);
    foreach ($words as $word) {
        $word = trim($word);
        if (!empty($word) && strpos($ip, $word) !== false) {
            return true;
        }
    }
    return false;
}

// Adiciona um IP à lista de banidos automaticamente
function dija_add_ip_to_disallowed_list($ip) {
    $list = get_option('disallowed_keys');
    
    // Se o IP já está lá, não faz nada
    if (strpos($list, $ip) !== false) return;

    $list .= "\n" . $ip;
    update_option('disallowed_keys', trim($list));
}
