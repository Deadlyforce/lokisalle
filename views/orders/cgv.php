<?php
$title = 'Conditions générales de vente';   
ob_start();
    echo $msg;
    ?>
    <h3>LokiSalle</h3>
    <p>ATTENTION : Le site « LokiSalle » est un site fictif.</p>

    <p>Conditions générales de vente.</p>
    <p>Il a été réalisé en Juin 2014 dans le cadre de l'atelier PHP prévu lors de la formation de Développeur Web de l’institut IFOCOP pour la session Diwoo 06. En conséquence : Les conditions générales de vente suivantes sont fictives.</p>

    <p>Article 1 : nature de la manifestation.</p>

    <p>La nature de la manifestation sera conforme à celle figurant dans les demandes et confirmations de réservation. Sont exclues les manifestations à caractère politique, religieux ou militant ainsi que toutes manifestations susceptibles de provoquer controverses ou troubles publics.</p>
        
    <p>Article 2 : nature des prestations et conditions financières.</p>

    <p>Les tarifs et les conditions de location sont ceux stipulés dans le devis dûment signé par le preneur et les conditions générales de vente. La location comprend la salle souhaitée avec la fourniture du mobilier (tables, chaises, tableaux) installée selon les souhaits du preneur, la fourniture des fluides (eau, électricité, chauffage) et la fourniture des boissons lors des pauses (thé, café, eau filtrée, gâteaux secs). Les prestations supplémentaires devront être spécifiées par écrit et feront l’objet d’une facturation à part.</p>
    
    <p>Article 3 : durée et horaires de la manifestation.</p>

    <p>La durée de la manifestation sera conforme aux horaires convenus au moment de la réservation. La prise de possession et la libération de l’espace loué devra intervenir dans le créneau horaire convenu. Pour tout dépassement d’horaires non prévu, un supplément sera facturé, dans la limite de deux heures complémentaires à la location. Ces dépassements ne sont accordables qu’en fonction des disponibilités.</p>
    
    <p>Article 4 : dépôts de matériels.</p>

    <p>En cas de dépôt de matériels avant ou après la location, ceux-ci restent sous la responsabilité du client. Les livraisons intervenant avant ou après la manifestation font l’objet d’un accord préalable fixant la nature, le conditionnement et le poids des objets, et les horaires. La reprise de votre matériel doit s’effectuer immédiatement après son utilisation.</p>
    
    <p>Article 5 : interdiction de fumer et de manger.</p>

    <p>Conformément au décret n° 92-478 du 29 mai 1992 et la loi du 1er janvier 2008, nous rappelons qu’il est interdit de fumer dans les lieux affectés à un usage collectif. Les repas ne peuvent être préparés ou être pris dans les locaux loués. Pour des raisons de sécurité, il est interdit d’y apporter des appareils de cuisson.</p>
    
    <p>Article 6 : confirmation des réservations.</p>

    <p>Une réservation sera considérée comme ferme et définitive par retours du devis signé et revêtu de la mention « Bon pour accord », ainsi que la réception d’un acompte de 50% du montant de location de la salle.</p>
    
    <p>Article 7 : annulation de la réservation.</p>

    <p>En cas d’annulation et quelle qu’en soit la cause, LokiSalle conservera les frais d’annulation suivant :
- Annulation 72 h avant : somme due = 50% du total global
- Annulation 24 h avant : somme due = 100% du total global</p>

<p>Article 8 : renonciation du fait du loueur.</p>

<p>En cas de renonciation du fait du loueur, lié à des impossibilités techniques ou en cas de force majeure, l’indemnisation ne pourra être supérieure aux sommes versées.
Article 9 : nombre de participants.</p>

<p>Le nombre de personnes indiqué pour chacune des salles ne doit pas être dépassé pour motif de sécurité.
Article 10 : restitution de la salle.</p>

<p>La salle mise à la disposition devra être restituée dans un état correct d’utilisation. En cas de salissures disproportionnées, LokiSalle se réserve le droit de facturer les frais de nettoyage.En cas de dégradations commises par le client, les réparations seront évaluées par procès-verbal et seront suivies d’une indemnisation par le client, sans délai de recours aux assurances.</p>

<p>Article 11 : acceptation des conditions de vente.</p>

<p>Cette acceptation est liée à la signature du bon de réservation.</p>

<p>Article 12 : contestation.</p>

<p>Toute contestation relative aux présentes prestations relève de la seule compétence des tribunaux du ressort de la Cour d’appel de Paris.</p>
    <?php
    $layout = ob_get_contents();
ob_clean();
include 'layouts/layout.php';
