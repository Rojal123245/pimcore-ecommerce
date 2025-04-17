<?php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;
use Pimcore\Model\Document\Editable\Area\Info;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ToccoOrgane extends AbstractTemplateAreabrick
{

    public function getName(): string
    {
        return 'Organe';
    }

    public function getDescription(): string
    {
        return 'Nur im Backend';
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
        //$url = 'https://swissengineering.tocco.ch/nice2/rest/entities/2.0/Institution/' . $id . '?_omitLinks=true';
        $url = 'https://swissengineering.tocco.ch/nice2/rest/entities/2.0/Institution/search?_omitLinks=true';

        //$where = '"where": "relInstitution_status.unique_id==\\"active\\"",';
        $where = '';

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
                    "relAddress.relAddress_type(label)",
                    "relInstitution_type(label)",
                    "relFunction.relUser(firstname,lastname,email,publish,publish_detail,preview_picture)"],
                ' . $where . '
                "limit": 100
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic cmVzdF9hcGlfYTRfd2Vic2l0ZTpETHU2QUM4bkd1aFZyM0ZmMmY0OU5TWG5Z',
                'Cookie: 912269046c0a7d5b2ce9c1b916ad2f42=b644a72d387b05486bef2bb927a46a80; JSESSIONID=A2E7BA2B12C9408D8800A7CCEC1AF339'
            )
        ));

        /* curl_setopt_array($curl, array(
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
                    "description",
                    "relAddress.relAddress_type(label)",
                    "relInstitution_status(unique_id,label)",
                    "relInstitution_type(label)",
                    "relFunction(valid_from,valid_till)",
                    "relFunction.relFunction_label.label_de",
                    "relFunction.relUser(firstname,lastname,email,publish,publish_detail,preview_picture)"],
                ' . $where . '
                "limit": 100
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic cmVzdF9hcGlfYTRfd2Vic2l0ZTpETHU2QUM4bkd1aFZyM0ZmMmY0OU5TWG5Z',
                'Cookie: 912269046c0a7d5b2ce9c1b916ad2f42=b644a72d387b05486bef2bb927a46a80; JSESSIONID=A2E7BA2B12C9408D8800A7CCEC1AF339'
            )
        )); */

        $response = curl_exec($curl);

        curl_close($curl);

        $json = json_decode($response);

        $organe = [];

        foreach ($json->data as $organ) {
            $entry = [];

            // ID
            $entry['id'] = $organ->key;

            // Titel
            $entry['label'] = $organ->paths->label->value;

            // Typ Organ: Fachgruppe, Sektion, etc.
            if ($organ->paths->relAddress->value) {
                $entry['typOrgan'] = $organ->paths->relAddress->value->paths->relAddress_type->value->paths->label->value;
            } else {
                $entry['typOrgan'] = '';
            }

            // Typ Vorstandsliste: Vorstand, Kommission, etc.
            if ($organ->paths->relInstitution_type->value) {
                $entry['typVorstandsliste'] = $organ->paths->relInstitution_type->value->paths->label->value;
            } else {
                $entry['typVorstandsliste'] = '';
            }
            
            $organe[] = $entry;
        }

        $info->setParam('organe', $organe);

        return null;
    }
    
}