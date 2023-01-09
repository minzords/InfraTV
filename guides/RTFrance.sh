#!/usr/bin/sh

# Headers
echo '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE tv SYSTEM "xmltv.dtd">
<!-- Generated with XML TV Fr v2.0.0 -->
<tv source-info-url="https://github.com/racacax/XML-TV-Fr" source-info-name="XML TV Fr" generator-info-name="XML TV Fr" generator-info-url="https://github.com/racacax/XML-TV-Fr">
  <channel id="RTFrance.tv">
    <display-name>RTFrance</display-name>
    <icon src="https://upload.wikimedia.org/wikipedia/fr/thumb/9/9e/RT_France.svg/1200px-RT_France.svg.png"/>
  </channel>' > rtfrance.xml

# Content
for n in $(seq 3 $(cat rtfrance.xls | wc -l))
#for n in `seq 3 3`
do

  echo '  <programme start="_DEBUT00 +0300" stop="_FIN00 +0300" channel="RTFrance.tv">
    <title lang="fr">_TITRE</title>
    <desc lang="fr">_DESCRIPTION</desc>
    <category lang="fr">_CATEGORIE</category>
    <icon src="https://i.f1g.fr/media/eidos/orig/2019/10/29/XVM5b3bd926-f975-11e9-ba50-e460b16f9313.jpg"/>
  </programme>' >> rtfrance.xml

  debut=$(awk -F '\t' '{print $1}' rtfrance.xls | sed -n "$n"p | awk -F/ '{printf 20"%s%s%s\n",$3,$2,$1}')
  debut=$debut$(awk -F '\t' '{print $2}' rtfrance.xls | sed -n "$n"p | sed -e "s|:||g")
  sed -i "s|_DEBUT|$debut|g" rtfrance.xml

  fin=$(awk -F '\t' '{print $1}' rtfrance.xls | sed -n "$n"p | awk -F/ '{printf 20"%s%s%s\n",$3,$2,$1}')
  fin=$fin$(awk -F '\t' '{print $3}' rtfrance.xls | sed -n "$n"p | sed -e "s|:||g")
  sed -i "s|_FIN|$fin|g" rtfrance.xml

  titre=$(awk -F '\t' '{print $4}' rtfrance.xls | sed -n "$n"p | sed -e "s|’|'|g")
  sed -i "s|_TITRE|$titre|g" rtfrance.xml

  description=$(awk -F '\t' '{print $7}' rtfrance.xls | sed -n "$n"p)
  sed -i "s|_DESCRIPTION|$description|g" rtfrance.xml

  categorie=$(awk -F '\t' '{print $6}' rtfrance.xls | sed -n "$n"p)
  sed -i "s|_CATEGORIE|$categorie|g" rtfrance.xml 

done

echo "</tv>" >> rtfrance.xml

