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
  turma() { # ordem dia horario ritmos(chaves, vírgula) nivel(chave) cor icone
    id=$($WP post create --post_type=turma --post_status=publish --menu_order="$1" --post_title="$2 · $3 · $4 $5" --porcelain)
    $WP post meta update "$id" dija_dia "$2" >/dev/null
    $WP post meta update "$id" dija_horario "$3" >/dev/null
    $WP post meta update "$id" dija_ritmo "$4" >/dev/null
    $WP post meta update "$id" dija_nivel "$5" >/dev/null
    $WP post meta update "$id" dija_cor "$6" >/dev/null
    $WP post meta update "$id" dija_icone "$7" >/dev/null
  }
  turma 1 SEGUNDA '19h às 20h' forro iniciante red sun
  turma 2 TERÇA '20h às 21h' forro intermediario teal moon
  turma 3 QUARTA '19h às 20h' forro avancado purple star-big
  turma 4 SEXTA '20h às 21h' 'samba,forro' avancado ochre sun2
fi

if [ -z "$($WP post list --post_type=evento --field=ID --post_status=any)" ]; then
  id=$($WP post create --post_type=evento --post_status=publish --menu_order=1 --post_title='LEVAMOS A DANÇA ATÉ VOCÊ!' --porcelain)
  $WP post meta update "$id" dija_descricao 'Apresentações culturais, festivais, aniversários, casamentos, eventos corporativos e muito mais!' >/dev/null
  $WP post meta update "$id" dija_botao_texto 'SAIBA MAIS' >/dev/null
  $WP post meta update "$id" dija_botao_link '/eventos/' >/dev/null
fi
# Locais de exemplo (Turmas → Locais)
local_id() { $WP term list local --name="$1" --field=term_id; }
if [ -z "$(local_id 'Studio Centro')" ]; then
  id=$($WP term create local 'Studio Centro' --porcelain)
  $WP term meta update "$id" dija_endereco 'Rua de Exemplo, 100 — Boa Vista, Recife/PE' >/dev/null
  $WP term meta update "$id" dija_ordem 1 >/dev/null
  id=$($WP term create local 'Espaço Beira-Mar' --porcelain)
  $WP term meta update "$id" dija_endereco 'Av. de Exemplo, 2000 — Boa Viagem, Recife/PE' >/dev/null
  $WP term meta update "$id" dija_ordem 2 >/dev/null
fi
A=$(local_id 'Studio Centro'); B=$(local_id 'Espaço Beira-Mar')

# Turmas em andamento sem local: as duas primeiras no Studio Centro, as demais no Espaço Beira-Mar
n=0
for id in $($WP post list --post_type=turma --orderby=menu_order --order=ASC --field=ID); do
  n=$((n+1))
  [ -z "$($WP post meta get "$id" dija_status 2>/dev/null)" ] && $WP post meta update "$id" dija_status andamento >/dev/null
  if [ -z "$($WP post term list "$id" local --field=term_id)" ]; then
    if [ $n -le 2 ]; then $WP post term set "$id" local "$A" --by=id >/dev/null; else $WP post term set "$id" local "$B" --by=id >/dev/null; fi
  fi
done

# Próximas turmas com imagem de divulgação (usa imagens do tema só como exemplo)
if [ -z "$($WP post list --post_type=turma --meta_key=dija_status --meta_value=proxima --field=ID)" ]; then
  IMG=/var/www/html/wp-content/themes/dija-theme/assets/img
  proxima() { # ordem dia horario ritmo nivel inicio cor icone local imagem
    id=$($WP post create --post_type=turma --post_status=publish --menu_order="$1" --post_title="Próxima: $4 $5" --porcelain)
    $WP post meta update "$id" dija_status proxima >/dev/null
    $WP post meta update "$id" dija_dia "$2" >/dev/null
    $WP post meta update "$id" dija_horario "$3" >/dev/null
    $WP post meta update "$id" dija_ritmo "$4" >/dev/null
    $WP post meta update "$id" dija_nivel "$5" >/dev/null
    $WP post meta update "$id" dija_inicio "$6" >/dev/null
    $WP post meta update "$id" dija_cor "$7" >/dev/null
    $WP post meta update "$id" dija_icone "$8" >/dev/null
    $WP post term set "$id" local "$9" --by=id >/dev/null
    $WP media import "$IMG/${10}" --post_id="$id" --featured_image --title="Divulgação $4 $5" >/dev/null
  }
  proxima 10 qui '19h às 20h' 'zouk' 'iniciante' 'Início em 08/01' purple star-big "$A" bg-hero.jpg
  proxima 11 sab '10h às 11h30' 'samba,forro,soltinho' 'infantil' 'Início em 10/01' ochre sun2 "$B" bg-sobre.jpg
fi

# Galeria de fotos de exemplo (Eventos → Fotos). Imagens do tema, só para teste.
if [ -z "$($WP post list --post_type=foto --field=ID)" ]; then
  IMG=/var/www/html/wp-content/themes/dija-theme/assets/img
  ev() { id=$($WP term list foto_evento --name="$1" --field=term_id); [ -n "$id" ] && echo "$id" || $WP term create foto_evento "$1" --porcelain; }
  E1=$(ev 'São João de Caruaru'); E2=$(ev 'Festival Forró de Rua'); E3=$(ev 'Casamento Ana & Pedro')
  foto() { # mes(AAAA-MM) evento_id imagem "tags,separadas" nome_evento
    id=$($WP post create --post_type=foto --post_status=publish --post_title="$5 · $(echo "$1" | awk -F- '{print $2"/"$1}')" --porcelain)
    $WP post meta update "$id" dija_data "$1" >/dev/null
    $WP post term set "$id" foto_evento "$2" --by=id >/dev/null
    $WP post term set "$id" foto_tag $(echo "$4" | tr ',' ' ') >/dev/null
    $WP media import "$IMG/$3" --post_id="$id" --featured_image --title="Foto $5" >/dev/null
  }
  foto 2026-06 "$E1" bg-hero.jpg 'forró,são-joão,apresentação' 'São João de Caruaru'
  foto 2026-06 "$E1" bg-eventos.jpg 'forró,quadrilha' 'São João de Caruaru'
  foto 2026-03 "$E2" bg-contato.jpg 'forró,rua' 'Festival Forró de Rua'
  foto 2026-03 "$E2" bg-turmas.jpg 'xote,rua' 'Festival Forró de Rua'
  foto 2025-11 "$E3" dija-sobre.jpg 'casamento,valsa' 'Casamento Ana & Pedro'
  foto 2025-11 "$E3" bg-sobre.jpg 'casamento,forró' 'Casamento Ana & Pedro'
  foto 2025-06 "$E1" bg-hero.jpg 'forró,são-joão' 'São João de Caruaru'
  foto 2025-06 "$E1" bg-eventos.jpg 'apresentação,são-joão' 'São João de Caruaru'
fi

echo "Conteúdo inicial pronto."
