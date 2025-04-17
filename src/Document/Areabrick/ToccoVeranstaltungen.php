<?php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;
use Pimcore\Model\Document\Editable\Area\Info;
//use Pimcore\Model\DataObject;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

use Carbon\Carbon;

class ToccoVeranstaltungen extends AbstractTemplateAreabrick
{

    public function getName(): string
    {
        return 'Veranstaltungen';
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

        $request = Request::createFromGlobals();

        // ID
        $id = $info->getDocumentElement('id');

        // Archiv
        $archiv = $info->getDocumentElement('archiv');

        // Soziale Veranstaltungen ausblenden
        $hideSocial = $info->getDocumentElement('hideSocial');

        // URL
        $url = 'https://swissengineering.tocco.ch/nice2/rest/entities/2.0/Event/search?_omitLinks=true';

        // JSON result
        $json = null;

        // Veranstaltungen
        $veranstaltungen = [];

        $fachgruppeLabel = '';

        if ($id && $archiv && $hideSocial) {

            // Alle Veranstaltungen
            if (!$id->getData()) {

                // Archiv
                if ($archiv->getData()) {
                    //$where = '"publication_internet_from<=datetime:\"now\" and publication_internet_till<datetime:\"now\""';

                    if ($hideSocial->getData()) {
                        $where = '"publication_internet_from<=datetime:\"now\" and publication_internet_till<datetime:\"now\" and relEvent_type.label != \"Soziale Veranstaltung\""';
                    } else {
                        $where = '"publication_internet_from<=datetime:\"now\" and publication_internet_till<datetime:\"now\""';
                    }

                    $sort = '"start_ical desc and first_course_date desc"';

                // Aktiv
                } else {
                    //$where = '"publication_internet_from<=datetime:\"now\" and publication_internet_till>=datetime:\"now\""';

                    if ($hideSocial->getData()) {
                        $where = '"publication_internet_from<=datetime:\"now\" and publication_internet_till>=datetime:\"now\" and relEvent_type.label != \"Soziale Veranstaltung\""';
                    } else {
                        $where = '"publication_internet_from<=datetime:\"now\" and publication_internet_till>=datetime:\"now\""';
                    }

                    $sort = '"start_ical asc and first_course_date asc"';

                }

            // Veranstaltungen nach Organ ID
            } else {

                // Archiv
                if ($archiv->getData()) {
                    //$where = '"publication_internet_from<=datetime:\"now\" and publication_internet_till<datetime:\"now\" and relOrganizer.relInstitution.pk == ' . $id->getData() . '"';

                    if ($hideSocial->getData()) {
                        $where = '"publication_internet_from<=datetime:\"now\" and publication_internet_till<datetime:\"now\" and relEvent_type.label != \"Soziale Veranstaltung\" and relOrganizer.relInstitution.pk == ' . $id->getData() . '"';
                    } else {
                        $where = '"publication_internet_from<=datetime:\"now\" and publication_internet_till<datetime:\"now\" and relOrganizer.relInstitution.pk == ' . $id->getData() . '"';
                    }

                    $sort = '"start_ical desc and first_course_date desc"';

                // Aktiv
                } else {
                    //$where = '"publication_internet_from<=datetime:\"now\" and publication_internet_till>=datetime:\"now\" and relOrganizer.relInstitution.pk == ' . $id->getData() . '"';

                    if ($hideSocial->getData()) {
                        $where = '"publication_internet_from<=datetime:\"now\" and publication_internet_till>=datetime:\"now\" and relEvent_type.label != \"Soziale Veranstaltung\" and relOrganizer.relInstitution.pk == ' . $id->getData() . '"';
                    } else {
                        $where = '"publication_internet_from<=datetime:\"now\" and publication_internet_till>=datetime:\"now\" and relOrganizer.relInstitution.pk == ' . $id->getData() . '"';
                    }

                    $sort = '"start_ical asc and first_course_date asc"';

                }
                
            }

            // cURL
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
                        "label_de",
                        "label_fr",
                        "label_it",
                        "label_en",
                        "city",
                        "first_course_date",
                        "last_course_date",
                        "start_ical",
                        "end_ical",
                        "event_nr",
                        "relEvent_category(unique_id,label_de,label_fr,label_it,label_en)",
                        "relOrganizer(relInstitution(label))",
                        "relEvent_type(unique_id,label_de,label_fr,label_it,label_en)",
                        "relEvent_status(unique_id,label)"
                    ],
                    "where": ' . $where . ',
                    "sort": ' . $sort . ',
                    "limit": 100
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Basic cmVzdF9hcGlfYTRfd2Vic2l0ZTpETHU2QUM4bkd1aFZyM0ZmMmY0OU5TWG5Z',
                    'Cookie: 912269046c0a7d5b2ce9c1b916ad2f42=b644a72d387b05486bef2bb927a46a80; JSESSIONID=77730F476092F5E06AD37FEA74F6EE90'
                )
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            
            // JSON
            $json = json_decode($response);

            // Name der Fachgruppe der gewählten ID
            $urlFachgruppe = 'https://swissengineering.tocco.ch/nice2/rest/entities/2.0/Institution/' . $id . '?_omitLinks=true';

            $curlFachgruppe = curl_init();

            curl_setopt_array($curlFachgruppe, array(
                CURLOPT_URL => $urlFachgruppe,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                    "paths": [
                        "label"
                    ]
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Basic cmVzdF9hcGlfYTRfd2Vic2l0ZTpETHU2QUM4bkd1aFZyM0ZmMmY0OU5TWG5Z',
                    'Cookie: 912269046c0a7d5b2ce9c1b916ad2f42=b644a72d387b05486bef2bb927a46a80; JSESSIONID=A2E7BA2B12C9408D8800A7CCEC1AF339'
                )
            ));

            $responseFachgruppe = curl_exec($curlFachgruppe);

            curl_close($curlFachgruppe);

            $jsonFachgruppe = json_decode($responseFachgruppe);

            //$fachgruppeLabel = '';

            if ($jsonFachgruppe) {
                $fachgruppeLabel = $jsonFachgruppe->paths->label->value;
            }

        }

        // Themen für Filter
        $themen = [];

        // Typen für Filter
        $typen = [];

        if ($json) {
            foreach ($json->data as $veranstaltung) {
                $entry = [];

                // ID
                $entry['id'] = $veranstaltung->key;

                // Veranstaltungs-Nummer
                $entry['event_nr'] = $veranstaltung->paths->event_nr->value;

                // Abgesagt
                if ($veranstaltung->paths->relEvent_status->value && strtolower($veranstaltung->paths->relEvent_status->value->paths->label->value) == 'abgesagt') {
                    $entry['abgesagt'] = true;
                } else {
                    $entry['abgesagt'] = false;
                }

                // Bezeichnung
                //$entry['bezeichnung'] = $veranstaltung->paths->label->value;
                $entry['bezeichnung']['de'] = $veranstaltung->paths->label_de->value;
                $entry['bezeichnung']['fr'] = $veranstaltung->paths->label_fr->value;
                $entry['bezeichnung']['it'] = $veranstaltung->paths->label_it->value;
                $entry['bezeichnung']['en'] = $veranstaltung->paths->label_en->value;

                // Startzeit
                if ($veranstaltung->paths->start_ical->value) {
                    $dtStartZeit = Carbon::parse($veranstaltung->paths->start_ical->value)->setTimezone('Europe/Berlin');
                    $entry['start_zeit'] = $dtStartZeit->format('H.i');
                } else {
                    $entry['start_zeit'] = null;
                }

                // Endezeit
                if ($veranstaltung->paths->end_ical->value) {
                    $dtEndeZeit = Carbon::parse($veranstaltung->paths->end_ical->value)->setTimezone('Europe/Berlin');
                    $entry['ende_zeit'] = $dtEndeZeit->format('H.i');
                } else {
                    $entry['ende_zeit'] = null;
                }

                // Startdatum
                if ($veranstaltung->paths->start_ical->value) {
                    $hasDate = true;
                    $dtStart = Carbon::parse($veranstaltung->paths->start_ical->value)->setTimezone('Europe/Berlin');
                    $entry['start_ical'] = $veranstaltung->paths->start_ical->value;
                    $entry['first_course_date'] = '';
                    $entry['dateSort'] = $dtStart->getTimestamp();
                    $entry['start_datum'] = $dtStart->format('d.m.Y');
                } else if ($veranstaltung->paths->first_course_date->value) {
                    $hasDate = true;
                    $dtStart = Carbon::parse($veranstaltung->paths->first_course_date->value)->setTimezone('Europe/Berlin');
                    $entry['start_ical'] = '';
                    $entry['first_course_date'] = $veranstaltung->paths->first_course_date->value;
                    $entry['dateSort'] = $dtStart->getTimestamp();
                    $entry['start_datum'] = $dtStart->format('d.m.Y');
                } else {
                    $hasDate = false;
                    $entry['start_ical'] = '';
                    $entry['first_course_date'] = '';
                    $entry['start_datum'] = '';

                    // if no startdate: move to end of list
                    // else move to start of list (archive has reverse sort order)
                    if (!$archiv->getData()) {
                        $entry['dateSort'] = Carbon::now()->addCentury()->getTimestamp();
                    } else {
                        $entry['dateSort'] = Carbon::now()->subCentury()->getTimestamp();
                    }
                }

                // Enddatum
                if ($veranstaltung->paths->end_ical->value) {
                    $dtEnd = Carbon::parse($veranstaltung->paths->end_ical->value)->setTimezone('Europe/Berlin');
                    $entry['end_ical'] = $veranstaltung->paths->end_ical->value;
                    $entry['last_course_date'] = '';
                    $entry['ende_datum'] = $dtEnd->format('d.m.Y');
                } else if ($veranstaltung->paths->last_course_date->value) {
                    $dtEnd = Carbon::parse($veranstaltung->paths->last_course_date->value)->setTimezone('Europe/Berlin');
                    $entry['end_ical'] = '';
                    $entry['last_course_date'] = $veranstaltung->paths->last_course_date->value;
                    $entry['ende_datum'] = $dtEnd->format('d.m.Y');
                } else {
                    $entry['end_ical'] = '';
                    $entry['last_course_date'] = '';
                    $entry['ende_datum'] = '';
                }

                // Datum aufgeteilt
                if ($hasDate) {
                    $entry['day'] = $dtStart->format('d');
                    $entry['month'] = $dtStart->locale('de')->shortMonthName;
                    $entry['year'] = $dtStart->year;
                } else {
                    $entry['day'] = null;
                    $entry['month'] = null;
                    $entry['year'] = null;
                }

                // hide if end date in the past, but still published, except for archive
                if (!$archiv->getData()) {
                    if ($entry['dateSort'] <= Carbon::now()->getTimestamp()) {
                        continue;
                    }
                }

                // Ort
                $entry['city'] = $veranstaltung->paths->city->value;

                // Organisator
                $entry['organizer'] = [];
                //$entry['organizer_company_c'] = $veranstaltung->paths->relOrganizer->value->paths->company_c->value;

                if ($veranstaltung->paths->relOrganizer->value) {
                    foreach ($veranstaltung->paths->relOrganizer->value->paths->relInstitution->value as $organizer) {
                        $entry['organizer'][] = $organizer->paths->label->value;
                    }
                }

                // Thema
                $entry['theme'] = [];
                foreach ($veranstaltung->paths->relEvent_category->value as $theme) {
                    $temp = [];

                    $temp['de'] = $theme->paths->label_de->value ? $theme->paths->label_de->value : null;
                    $temp['fr'] = $theme->paths->label_fr->value ? $theme->paths->label_fr->value : null;
                    $temp['it'] = $theme->paths->label_it->value ? $theme->paths->label_it->value : null;
                    $temp['en'] = $theme->paths->label_en->value ? $theme->paths->label_en->value : null;

                    $entry['theme'][] = $temp;

                    $themen[] = $temp;
                }

                // Typ
                if ($veranstaltung->paths->relEvent_type->value) {

                    /* $entry['typ']['de'] = $veranstaltung->paths->relEvent_type->value->paths->label_de->value;
                    $entry['typ']['fr'] = $veranstaltung->paths->relEvent_type->value->paths->label_fr->value;
                    $entry['typ']['it'] = $veranstaltung->paths->relEvent_type->value->paths->label_it->value;
                    $entry['typ']['en'] = $veranstaltung->paths->relEvent_type->value->paths->label_en->value; */

                    $temp = [];

                    $temp['de'] = $veranstaltung->paths->relEvent_type->value->paths->label_de->value;
                    $temp['fr'] = $veranstaltung->paths->relEvent_type->value->paths->label_fr->value;
                    $temp['it'] = $veranstaltung->paths->relEvent_type->value->paths->label_it->value;
                    $temp['en'] = $veranstaltung->paths->relEvent_type->value->paths->label_en->value;

                    $entry['typ'] = $temp;

                    $typen[] = $temp;
                } else {
                    $entry['typ']['de'] = null;
                    $entry['typ']['fr'] = null;
                    $entry['typ']['it'] = null;
                    $entry['typ']['en'] = null;
                }
                
                $veranstaltungen[] = $entry;
            }
        }

        // sort by:
        // 1. start_ical
        // 2. first_course_date
        // reverse for archive

        if ($archiv && $archiv->getData()) {
            usort($veranstaltungen, fn($a, $b) => $b['dateSort'] <=> $a['dateSort']);
        } else {
            usort($veranstaltungen, fn($a, $b) => $a['dateSort'] <=> $b['dateSort']);
        }

        // unique themen, sorted alphabetically
        $themenUnique = \App\Controller\HelperController::array_unique_multidimensional($themen);
        usort($themenUnique, fn($a, $b) => strcmp($a[$request->getLocale()], $b[$request->getLocale()]));

        // unique typen, sorted alphabetically
        $typenUnique = \App\Controller\HelperController::array_unique_multidimensional($typen);
        usort($typenUnique, fn($a, $b) => strcmp($a[$request->getLocale()], $b[$request->getLocale()]));

        $info->setParam('veranstaltungen', $veranstaltungen);
        $info->setParam('themen', $themenUnique);
        $info->setParam('typen', $typenUnique);
        $info->setParam('fachgruppeLabel', $fachgruppeLabel);

        return null;
    }
    
}