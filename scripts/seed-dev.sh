#!/bin/sh
# Conteúdo inicial do ambiente local (idempotente: não duplica se já existir).
# Uso: sh scripts/seed-dev.sh
set -e
WP="sh $(dirname "$0")/wp.sh"

$WP option update permalink_structure '/%postname%/' >/dev/null
$WP rewrite flush >/dev/null

page() { # slug título conteúdo
  id=$($WP post list --post_type=page --name="$1" --field=ID --post_status=any)
  if [ -z "$id" ]; then
    $WP post create --post_type=page --post_status=publish --post_name="$1" --post_title="$2" --post_content="$3" --porcelain
  else echo "$id"; fi
}

BIO='<!-- wp:paragraph --><p>Sou professora de dança de salão especialista em Forró, com mais de 15 anos de dedicação ao ensino.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Minha missão é ajudar os alunos a desenvolverem consciência corporal, musicalidade e uma conexão genuína na dança, transformando cada passo em um diálogo sem palavras.</p><!-- /wp:paragraph -->'
page bio 'Bio' "$BIO" >/dev/null
page contato 'Contato' '' >/dev/null

if [ -z "$($WP post list --post_type=turma --field=ID --post_status=any)" ]; then
  turma() { # ordem dia horario ritmo nivel cor icone
    id=$($WP post create --post_type=turma --post_status=publish --menu_order="$1" --post_title="$2 · $3 · $4 $5" --porcelain)
    $WP post meta update "$id" dija_dia "$2" >/dev/null
    $WP post meta update "$id" dija_horario "$3" >/dev/null
    $WP post meta update "$id" dija_ritmo "$4" >/dev/null
    $WP post meta update "$id" dija_nivel "$5" >/dev/null
    $WP post meta update "$id" dija_cor "$6" >/dev/null
    $WP post meta update "$id" dija_icone "$7" >/dev/null
  }
  turma 1 SEGUNDA '19h às 20h' FORRÓ INICIANTE red sun
  turma 2 TERÇA '20h às 21h' FORRÓ INTERMEDIÁRIO teal moon
  turma 3 QUARTA '19h às 20h' FORRÓ AVANÇADO purple star-big
  turma 4 SEXTA '20h às 21h' FORRÓ AVANÇADO ochre sun2
fi

if [ -z "$($WP post list --post_type=evento --field=ID --post_status=any)" ]; then
  id=$($WP post create --post_type=evento --post_status=publish --menu_order=1 --post_title='LEVAMOS A DANÇA ATÉ VOCÊ!' --porcelain)
  $WP post meta update "$id" dija_descricao 'Apresentações culturais, festivais, aniversários, casamentos, eventos corporativos e muito mais!' >/dev/null
  $WP post meta update "$id" dija_botao_texto 'SAIBA MAIS' >/dev/null
  $WP post meta update "$id" dija_botao_link '/contato/' >/dev/null
fi
echo "Conteúdo inicial pronto."
