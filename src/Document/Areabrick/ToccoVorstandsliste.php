<?php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;
use Pimcore\Model\Document\Editable\Area\Info;
//use Pimcore\Model\DataObject;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ToccoVorstandsliste extends AbstractTemplateAreabrick
{

    public function getName(): string
    {
        return 'Vorstandsliste';
    }

    public function getDescription(): string
    {
        return '';
    }

    public function needsReload(): bool
    {
        return true;
    }

    public function getHtmlTagOpen(Info $info): string
    {
        return '';
    }

    public function getHtmlTagClose(Info $info): string
    {
        return '';
    }

    public function action(Info $info): ?RedirectResponse
    {

        /*
        Das Attribut ist auf der Entität «User», Feld «relUser_code3».
        Wenn der Wert «hide_mail_from_list» gesetzt ist (habe ich auf meiner Person definiert) soll die E-Mail-Adresse nicht angezeigt werden.
        Bitte E-Mail nicht im Klartext anzeigen (-> encrypt).
        Die Funktionen bitte zusammenfassen und nicht mehrmals die gleiche Person anzeigen.
        */

        // ID
        $id = $info->getDocumentElement('id');

        // Vorstandsliste
        $vorstandsliste = [];
        $vorstandsliste['id'] = '';
        $vorstandsliste['label'] = '';
        $vorstandsliste['personen'] = [];

        if ($id && $id->getData()) {

            $url = 'https://swissengineering.tocco.ch/nice2/rest/entities/2.0/Institution/' . $id . '?_omitLinks=true';

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                    "paths": [
                        "label",
                        "relFunction(valid_till)",
                        "relFunction.relFunction_label(label_de,label_fr,label_it)",
                        "relFunction.relUser(pk,firstname,lastname,email,relUser_code3,preview_picture)"
                    ]
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Basic cmVzdF9hcGlfYTRfd2Vic2l0ZTpETHU2QUM4bkd1aFZyM0ZmMmY0OU5TWG5Z',
                    'Cookie: 912269046c0a7d5b2ce9c1b916ad2f42=b644a72d387b05486bef2bb927a46a80; JSESSIONID=A2E7BA2B12C9408D8800A7CCEC1AF339'
                )
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $json = json_decode($response);

            /*
            data: array von allen Fachgruppen
                key: ID
                paths
                    relAddress
                        value
                            paths
                                relAddress_type
                                    value
                                        paths
                                            label
                                                value: Region, Sektion, Fachgruppe
                    relFunction
                        value: array von Personen in der Fachgruppe
                            paths
                                relFunction_label
                                    value
                                        paths
                                            label_de
                                                value: Name der Funktion
                                relUser
                                    value
                                        paths
                                            firstname
                                                value
                                            lastname
                                                value
                                            email
                                                value
                                            preview_picture
                                                value
                                                    binaryLink: Vorschaubild
                                                    thumbnailLink: Vorschaubild
                                            detail_picture
                    label
                        value: Nach der Fachgruppe
            */

            if ($json) {

                // ID
                $vorstandsliste['id'] = $json->key;

                // Name
                $vorstandsliste['label'] = $json->paths->label->value;

                // Personen
                foreach ($json->paths->relFunction->value as $person) {

                    // Nur Personen mit aktiver Funktion
                    if ($person->paths->valid_till->value != null && $person->paths->valid_till->value < date('Y-m-d')) {
                        continue;
                    }

                    $personTemp = [];

                    // pk
                    $personTemp['pk'] = $person->paths->relUser->value->paths->pk->value;

                    // Bei Personen mit mehrfachen Funktionen nur die weitere Funktion hinzufügen
                    $i = 0;
                    foreach ($vorstandsliste['personen'] as $personExists) {
                        if ($personExists['pk'] == $personTemp['pk']) {
                            
                            // Funktion der Person
                            $function = [];

                            $function['de'] = $person->paths->relFunction_label->value->paths->label_de->value;
                            $function['fr'] = $person->paths->relFunction_label->value->paths->label_fr->value;
                            $function['it'] = $person->paths->relFunction_label->value->paths->label_it->value;

                            $vorstandsliste['personen'][$i]['function'][] = $function;

                            continue 2;
                        }

                        $i++;
                    }

                    // Name
                    $personTemp['firstname'] = $person->paths->relUser->value->paths->firstname->value;
                    $personTemp['lastname'] = $person->paths->relUser->value->paths->lastname->value;

                    // E-Mail encoded
                    //$personTemp['email'] = $person->paths->relUser->value->paths->email->value;
                    $personTemp['email'] = \App\Controller\HelperController::encode_email('mailto:' . $person->paths->relUser->value->paths->email->value);

                    // E-Mail ausblenden
                    if ($person->paths->relUser->value->paths->relUser_code3->value) {
                        $personTemp['hideEmail'] = true;
                    } else {
                        $personTemp['hideEmail'] = false;
                    }

                    // Funktion der Person
                    $function = [];

                    $function['de'] = $person->paths->relFunction_label->value->paths->label_de->value;
                    $function['fr'] = $person->paths->relFunction_label->value->paths->label_fr->value;
                    $function['it'] = $person->paths->relFunction_label->value->paths->label_it->value;

                    $personTemp['function'][] = $function;

                    // Bild
                    if ($person->paths->relUser->value->paths->preview_picture->value) {
                        $personTemp['preview_picture'] = $person->paths->relUser->value->paths->preview_picture->value->binaryLink;
                    } else {
                        $personTemp['preview_picture'] = null;
                    }

                    /* if ($person->paths->relUser->value->paths->detail_picture->value) {
                        $personTemp['detail_picture'] = $person->paths->relUser->value->paths->detail_picture->value->binaryLink;
                    } else {
                        $personTemp['detail_picture'] = null;
                    } */

                    $vorstandsliste['personen'][] = $personTemp;
                }

                // sort by lastname, ascending
                usort($vorstandsliste['personen'], function($a, $b) {
                    return $a['lastname'] > $b['lastname'];
                });

            }

        }

        $info->setParam('vorstandsliste', $vorstandsliste);

        return null;
    }
    
}