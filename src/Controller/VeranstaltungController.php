<?php

namespace App\Controller;

use Pimcore\Controller\FrontendController;
//use Pimcore\Model\DataObject;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

use Carbon\Carbon;
//use Sabre\VObject;

//use Symfony\Component\Mime\Part\DataPart;

class VeranstaltungController extends FrontendController
{

    // -----------------------------------
    // DETAIL
    // -----------------------------------

    /**
     * @Route(
     *      "/{_locale}/{path}/{title}_v{id}", 
     *      name="veranstaltung-detail", 
     *      requirements={
     *          "path"=".*",
     *          "title"="[\w-]+", 
     *          "id"="\d+"
     *      }
     * )
     */
    public function detail(
        Request $request, 
        TranslatorInterface $translator
    ) {

        // device
        $device = \Pimcore\Tool\DeviceDetector::getInstance();

        // parameters
        $routeParameters = $request->attributes->get('_route_params');
        $id = $routeParameters['id'];

        /*
            Stammdaten
                Bezeichnung DE FR IT EN -> label_de
                Typ (Veranstaltung, Fachveranstaltung, Besichtigung/Führung, Social Event, Versammlung]) -> relEvent_type
                Lead -> description
                Programm -> programme
                Sprache -> relEvent_language
                Lehrmethode -> relEvent_teaching_method
                Thema -> relEvent_category (Mobilität, Sicherheit, etc.)
                Organisator -> relOrganizer

            Termin- und Lektionsplanung
                Start -> start_ical | first_course_date
                Ende -> end_ical | last_course_date

            Publikation
                Publikation von -> publication_internet_from
                Publikation bis -> publication_internet_till

            Anmeldung
                Internet von (Anmeldung von) -> registration_internet_from
                Internet bis (Anmeldung bis) -> registration_internet_till
                Abmeldung bis -> cancellation_till
                Überbuchung (Warteliste, Überbuchung, Abweisung) -> relEvent_overbooking
                Orange Ampel ab (Wert) -> orange_light

            Dokumente
                Ausschreibung -> course_details
                Lageplan -> location_map
                Kursunterlagen -> course_documents
                Anmeldeformular -> registration_form
                Kontakt-Bild -> contact_image
                Bild 1 -> image_1
                Bild 2 -> image_2
                Bild 3 -> image_3

            Status
                Status (Offen, Durchgeführt, Abgesagt, Archiviert) -> relEvent_status

            Teilnehmer
                + Teilnehmer maximal -> participation_max
                + Teilnehmer minimal -> participation_min
                + Teilnehmer aktuell (Anmeldungen)-> registration

            Texte Publikation
                + Ziele -> targets
                + Rahmenprogramm -> supporting_program (alt: contents)
                + Wegbeschreibung -> directions (alt: sequence)
                + Zielgruppe -> target_group
                + Anforderungen -> requirements
                + Lehrmethode -> teaching_method
                + Vorwissen -> previous_knowledge
                + Zertifikat -> certificate
                + Dozenten / Referenten -> lecturer

                - Lehrmittel -> teaching_aid
                - Dokumentation -> documentation
                - Vorbereitung -> preparation
                - Organisation -> organisation

                + Kontakt (Name) -> contact_person
                + Kontakt E-Mail -> contact_email
                + Kontakt Telefon -> contact_phone

                + Lokalität -> location
                + Raum -> room
                + Ort -> city

                - Dauer -> duration

                + Treffpunkt -> meeting_point
                + Kosten -> costs
                + Link -> links

        */

        $url = 'https://swissengineering.tocco.ch/nice2/rest/entities/2.0/Event/' . $id . '?_omitLinks=true';

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
                    "relEvent_type(unique_id,label_de,label_fr,label_it,label_en)",
                    "description",
                    "programme",
                    "relEvent_language(label_de,label_fr,label_it,label_en)",
                    "relEvent_teaching_method(label_de,label_fr,label_it,label_en)",
                    "relEvent_category(unique_id,label_de,label_fr,label_it,label_en,description,description_de)",
                    "relOrganizer(company_c,zip_c,city_c)",
                    "first_course_date",
                    "last_course_date",
                    "start_ical",
                    "end_ical",
                    "publication_internet_from",
                    "publication_internet_till",
                    "registration_internet_from",
                    "registration_internet_till",
                    "cancellation_till",
                    "relEvent_overbooking",
                    "orange_light",
                    "course_details",
                    "location_map",
                    "course_documents",
                    "registration_form",
                    "contact_image",
                    "image_1",
                    "image_2",
                    "image_3",
                    "duration",
                    "relEvent_status(unique_id,label)",
                    "participation_max",
                    "participation_min",
                    "registration",
                    "working_hours",
                    "targets",
                    "contents",
                    "sequence",
                    "target_group",
                    "requirements",
                    "teaching_method",
                    "previous_knowledge",
                    "certificate",
                    "lecturer",
                    "contact_person",
                    "contact_email",
                    "organization",
                    "city",
                    "meeting_point",
                    "costs",
                    "links",
                    "supporting_program",
                    "directions",
                    "room",
                    "location",
                    "contact_phone"
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
        $jsonError = json_decode($response, true);

        //dd($json);

        // Veranstaltung
        $veranstaltung = [];

        // Veranstaltung existiert
        if (array_key_exists('key', $jsonError)) {

            // ID
            $veranstaltung['id'] = $json->key;

            /* dd(Carbon::parse($json->paths->publication_internet_till->value)->setTimezone('Europe/Berlin')); */
            //dd(Carbon::parse($json->paths->publication_internet_till->value)->setTimezone('Europe/Berlin'));
            //dd(Carbon::now()->setTimezone('Europe/Berlin'));
            //2023-10-30 11:00:00.0 Europe/Berlin (+01:00)
            //2023-10-20 15:46:52.016114 Europe/Berlin (+02:00)

            //dd(Carbon::parse($json->paths->publication_internet_till->value)->setTimezone('Europe/Berlin'));

            // Archiv
            $dtPublicationInternetTill = Carbon::parse($json->paths->publication_internet_till->value)->setTimezone('Europe/Berlin');
            $dtNow = Carbon::now()->setTimezone('Europe/Berlin');

            //if ($dtPublicationInternetTill->format('Y-m-d h:i') < $dtNow->format('Y-m-d h:i')) {
            if ($dtPublicationInternetTill < $dtNow) {
                $veranstaltung['archiv'] = true;
            } else {
                $veranstaltung['archiv'] = false;
            }

            // Bezeichnung
            $veranstaltung['bezeichnung']['de'] = $json->paths->label_de->value;
            $veranstaltung['bezeichnung']['fr'] = $json->paths->label_fr->value;
            $veranstaltung['bezeichnung']['it'] = $json->paths->label_it->value;
            $veranstaltung['bezeichnung']['en'] = $json->paths->label_en->value;

            // Typ
            if ($json->paths->relEvent_type->value) {
                $veranstaltung['typ']['de'] = $json->paths->relEvent_type->value->paths->label_de->value;
                $veranstaltung['typ']['fr'] = $json->paths->relEvent_type->value->paths->label_fr->value;
                $veranstaltung['typ']['it'] = $json->paths->relEvent_type->value->paths->label_it->value;
                $veranstaltung['typ']['en'] = $json->paths->relEvent_type->value->paths->label_en->value;
            } else {
                $veranstaltung['typ']['de'] = null;
                $veranstaltung['typ']['fr'] = null;
                $veranstaltung['typ']['it'] = null;
                $veranstaltung['typ']['en'] = null;
            }

            // Lead / Beschreibung
            $veranstaltung['lead'] = $json->paths->description->value;

            // Programm / Beschreibung Web
            $veranstaltung['programm'] = $json->paths->programme->value;

            // Sprache
            if ($json->paths->relEvent_language->value) {
                $veranstaltung['sprache']['de'] = $json->paths->relEvent_language->value->paths->label_de->value;
                $veranstaltung['sprache']['fr'] = $json->paths->relEvent_language->value->paths->label_fr->value;
                $veranstaltung['sprache']['it'] = $json->paths->relEvent_language->value->paths->label_it->value;
                $veranstaltung['sprache']['en'] = $json->paths->relEvent_language->value->paths->label_en->value;
            } else {
                $veranstaltung['sprache']['de'] = null;
                $veranstaltung['sprache']['fr'] = null;
                $veranstaltung['sprache']['it'] = null;
                $veranstaltung['sprache']['en'] = null;
            }

            // Lehrmethode
            if ($json->paths->relEvent_teaching_method->value) {
                $veranstaltung['lehrmethode_event']['de'] = $json->paths->relEvent_teaching_method->value->paths->label_de->value;
                $veranstaltung['lehrmethode_event']['fr'] = $json->paths->relEvent_teaching_method->value->paths->label_fr->value;
                $veranstaltung['lehrmethode_event']['it'] = $json->paths->relEvent_teaching_method->value->paths->label_it->value;
                $veranstaltung['lehrmethode_event']['en'] = $json->paths->relEvent_teaching_method->value->paths->label_en->value;
            } else {
                $veranstaltung['lehrmethode_event']['de'] = null;
                $veranstaltung['lehrmethode_event']['fr'] = null;
                $veranstaltung['lehrmethode_event']['it'] = null;
                $veranstaltung['lehrmethode_event']['en'] = null;
            }

            // Thema
            // array mit mehreren Themen in mehreren Sprachen
            $themen = [];

            foreach ($json->paths->relEvent_category->value as $theme) {
                $thema = [];

                $thema['de'] = $theme->paths->label_de->value;
                $thema['fr'] = $theme->paths->label_fr->value;
                $thema['it'] = $theme->paths->label_it->value;
                $thema['en'] = $theme->paths->label_en->value;

                $themen[] = $thema;
            }

            $veranstaltung['themen'] = $themen;
            
            // Organisator
            if ($json->paths->relOrganizer->value) {
                $veranstaltung['organisator']['firma'] = $json->paths->relOrganizer->value->paths->company_c->value;
                $veranstaltung['organisator']['plz'] = $json->paths->relOrganizer->value->paths->zip_c->value;
                $veranstaltung['organisator']['ort'] = $json->paths->relOrganizer->value->paths->city_c->value;
            } else {
                $veranstaltung['organisator']['firma'] = null;
                $veranstaltung['organisator']['plz'] = null;
                $veranstaltung['organisator']['ort'] = null;
            }

            // Start
            if ($json->paths->start_ical->value) {
                $dtStart = Carbon::parse($json->paths->start_ical->value)->setTimezone('Europe/Berlin');
            } else {
                $dtStart = Carbon::parse($json->paths->first_course_date->value)->setTimezone('Europe/Berlin');
            }

            $veranstaltung['start'] = $dtStart->format('d.m.Y');

            // Ende
            if ($json->paths->end_ical->value) {
                $dtEnde = Carbon::parse($json->paths->end_ical->value)->setTimezone('Europe/Berlin');
            } else {
                $dtEnde = Carbon::parse($json->paths->last_course_date->value)->setTimezone('Europe/Berlin');
            }

            $veranstaltung['ende'] = $dtEnde->format('d.m.Y');

            // Datum aufgeteilt
            $veranstaltung['day'] = $dtStart->format('d');
            $veranstaltung['month'] = $dtStart->locale('de')->shortMonthName;
            $veranstaltung['year'] = $dtStart->year;

            // Startzeit
            if ($json->paths->start_ical->value) {
                $dt = Carbon::parse($json->paths->start_ical->value)->setTimezone('Europe/Berlin');
                $veranstaltung['start_zeit'] = $dt->format('H:i');
            } else {
                $veranstaltung['start_zeit'] = null;
            }

            // Endezeit
            if ($json->paths->end_ical->value) {
                $dt = Carbon::parse($json->paths->end_ical->value)->setTimezone('Europe/Berlin');
                $veranstaltung['ende_zeit'] = $dt->format('H:i');
            } else {
                $veranstaltung['ende_zeit'] = null;
            }

            // Zeiten
            if ($json->paths->start_ical->value) {
                if ($veranstaltung['start_zeit'] && $veranstaltung['ende_zeit']) {
                    $veranstaltung['zeiten'] = $dtStart->format('d.m.Y, H.i') . ' Uhr bis<br>' . $dtEnde->format('d.m.Y, H.i') . ' Uhr';
                } else {
                    $veranstaltung['zeiten'] = $dtStart->format('d.m.Y, H.i') . ' Uhr';
                }
            } else {
                $veranstaltung['zeiten'] = null;
            }

            // Lokalität
            $veranstaltung['lokalitaet'] = $json->paths->location->value;

            // Raum
            $veranstaltung['raum'] = $json->paths->room->value;

            // PLZ (Dauer) -> duration
            //$veranstaltung['plz'] = $json->paths->duration->value;

            // Ort
            $veranstaltung['ort'] = $json->paths->city->value;

            // Ausschreibung
            // Link zu PDF
            $veranstaltung['ausschreibung'] = $json->paths->course_details->value;

            // Lageplan
            $veranstaltung['lageplan'] = $json->paths->location_map->value;

            // Kursunterlagen
            $veranstaltung['kursunterlagen'] = $json->paths->course_documents->value;

            // Anmeldeformular
            $veranstaltung['anmeldeformular'] = $json->paths->registration_form->value;

            // Kontakt-Bild
            $veranstaltung['kontaktBild'] = $json->paths->contact_image->value;

            // Bild 1
            if ($json->paths->image_1->value) {
                $veranstaltung['bild1'] = $json->paths->image_1->value->binaryLink;


                /* $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => 'https://swissengineering.tocco.ch/6ccff33/108/8---herbstseminar23---18.-FAEL-Herbstseminar-Windenergie-Bild-1.png?version=1',
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_HTTPHEADER => array(
                    'Authorization: Basic cmVzdF9hcGlfYTRfd2Vic2l0ZTpETHU2QUM4bkd1aFZyM0ZmMmY0OU5TWG5Z'
                  ),
                ));
                
                $response = curl_exec($curl);
                
                curl_close($curl);
                echo $response; */



                /* $auth = base64_encode("rest_api_a4_website:DLu6AC8nGuhVr3Ff2f49NSXnY");

                $context = stream_context_create([
                    "http" => [
                        'method' => 'GET',
                        "header" => "Authorization: Basic $auth"
                    ]
                ]);

                $data = file_get_contents("https://swissengineering.tocco.ch/6ccff33/108/8---herbstseminar23---18.-FAEL-Herbstseminar-Windenergie-Bild-1.png?version=1", false, $context );

                dd($data); */

                
                //$veranstaltung['bild1'] = $response;

            } else {
                $veranstaltung['bild1'] = null;
            }

            // Bild 2
            if ($json->paths->image_2->value) {
                $veranstaltung['bild2'] = $json->paths->image_2->value->binaryLink;
            } else {
                $veranstaltung['bild2'] = null;
            }

            // Bild 3
            if ($json->paths->image_3->value) {
                $veranstaltung['bild3'] = $json->paths->image_3->value->binaryLink;
            } else {
                $veranstaltung['bild3'] = null;
            }

            // Status
            //$veranstaltung['status_event'] = $json->paths->relEvent_status->value->paths->label->value;

            // Abgesagt
            if ($json->paths->relEvent_status->value && strtolower($json->paths->relEvent_status->value->paths->label->value) == 'abgesagt') {
                $veranstaltung['abgesagt'] = true;
            } else {
                $veranstaltung['abgesagt'] = false;
            }

            // Ampel
            $orangeLight = $json->paths->orange_light->value;
            $registration = $json->paths->registration->value;
            $teilnehmerMax = $json->paths->participation_max->value;

            $veranstaltung['orangeLight'] = $orangeLight;
            $veranstaltung['anmeldungen'] = $registration;

            /* if ($teilnehmerMax - $registration > $orangeLight) {
                $veranstaltung['status'] = 'freie Plätze';
            } else if ($teilnehmerMax - $registration <= $orangeLight) {
                $veranstaltung['status'] = 'wenige freie Plätze';
            } else {
                $veranstaltung['status'] = 'ausgebucht';
            } */

            /* if ($registration < $orangeLight) {
                $veranstaltung['status'] = 'freie Plätze';
            } else if ($registration >= $orangeLight) {
                $veranstaltung['status'] = 'wenige freie Plätze';
            } else {
                $veranstaltung['status'] = 'ausgebucht';
            } */

            if ($registration < $orangeLight) {
                $veranstaltung['status'] = 'freie Plätze';
                $veranstaltung['orange_light'] = false;
            } else {
                $veranstaltung['status'] = 'wenige freie Plätze';
                $veranstaltung['orange_light'] = true;
            }

            // max Teilnehmer
            $veranstaltung['teilnehmer_max'] = $teilnehmerMax;

            // Anmeldung Internet von
            if ($json->paths->registration_internet_from->value) {
                $dtAnmeldungVon = Carbon::parse($json->paths->registration_internet_from->value)->setTimezone('Europe/Berlin');
            } else {
                $dtAnmeldungVon = null;
            }

            // Anmeldung Internet bis
            if ($json->paths->registration_internet_till->value) {
                $dtAnmeldungBis = Carbon::parse($json->paths->registration_internet_till->value)->setTimezone('Europe/Berlin');
                $veranstaltung['anmeldeschluss'] = $dtAnmeldungBis->format('d.m.Y, H.i') . ' Uhr';
            } else {
                $dtAnmeldungBis = null;
                $veranstaltung['anmeldeschluss'] = null;
            }

            // Ziele
            $veranstaltung['ziele'] = $json->paths->targets->value;

            // Rahmenprogramm
            $veranstaltung['rahmenprogramm'] = $json->paths->supporting_program->value;

            // Wegbeschreibung
            $veranstaltung['wegbeschreibung'] = $json->paths->directions->value;

            // Zielgruppe
            $veranstaltung['zielgruppe'] = $json->paths->target_group->value;

            // Anforderungen
            $veranstaltung['anforderungen'] = $json->paths->requirements->value;

            // Lehrmethode
            $veranstaltung['lehrmethode'] = $json->paths->teaching_method->value;

            // Vorwissen
            $veranstaltung['vorwissen'] = $json->paths->previous_knowledge->value;

            // Zertifikat
            $veranstaltung['zertifikat'] = $json->paths->certificate->value;

            // Organisation
            //$veranstaltung['organisation'] = $json->paths->organisation->value;

            // Dozenten
            $veranstaltung['dozenten'] = $json->paths->lecturer->value;

            // Kontakt Name
            $veranstaltung['kontaktName'] = $json->paths->contact_person->value;

            // Kontakt E-Mail
            $veranstaltung['kontaktEmail'] = $json->paths->contact_email->value;

            // Kontakt Telefon
            $veranstaltung['kontaktTelefon'] = $json->paths->contact_phone->value;
            //$veranstaltung['kontaktTelefon'] = $json->paths->organization->value;

            // Treffpunkt
            $veranstaltung['treffpunkt'] = $json->paths->meeting_point->value;

            // Kosten
            $veranstaltung['kosten'] = $json->paths->costs->value;

            // Link
            $veranstaltung['link'] = $json->paths->links->value;

            /* // Kalender, wird von Tocco generiert
            $vcalendar = new VObject\Component\VCalendar([
                'VEVENT' => [
                    'SUMMARY' => $json->paths->label_de->value,
                    'DTSTART' => $dtStart,
                    'DTEND'   => $dtEnde
                ]
            ]);
            
            echo $vcalendar->serialize(); */

            // Anmelde Link in Tocco
            // ausblenden wenn Anmeldeschluss in Vergangenheit liegt
            // ausblenden wenn registration_internet_from in Zukunft liegt oder leer ist
            // ausblenden wenn registration_internet_till in Vergangenheit liegt
            if (!$dtAnmeldungVon || $dtAnmeldungVon > Carbon::now() || $dtAnmeldungBis < Carbon::now() || $dtStart < Carbon::now()) {
                $veranstaltung['anmelde_link']['de'] = null;
                $veranstaltung['anmelde_link']['fr'] = null;
                $veranstaltung['anmelde_link']['it'] = null;
            } else {
                $veranstaltung['anmelde_link']['de'] = 'https://web.swissengineering.tocco.ch/de/public-widgets/veranstaltungen/?eventKey=' . $json->key;
                $veranstaltung['anmelde_link']['fr'] = 'https://web.swissengineering.tocco.ch/fr/public-widgets/evenements/?eventKey=' . $json->key;
                $veranstaltung['anmelde_link']['it'] = 'https://web.swissengineering.tocco.ch/it/public-widgets/evento/?eventKey=' . $json->key;
            }

            //dd($veranstaltung);

            return $this->render('detailpages/tocco/veranstaltung/view.html.twig', [
                'device' => $device->getDevice(),
                'veranstaltung' => $veranstaltung,
                'weitereVeranstaltungen' => $this->weitereVeranstaltungen()
            ]);
        } else {
            throw new NotFoundHttpException("Event not found");
        }

    }

    public function weitereVeranstaltungen()
    {

        // URL
        $url = 'https://swissengineering.tocco.ch/nice2/rest/entities/2.0/Event/search?_omitLinks=true';

        // JSON result
        $json = null;

        // Veranstaltungen
        $veranstaltungen = [];

        // where
        $where = '"publication_internet_from<=datetime:\"now\" and publication_internet_till>=datetime:\"now\""';

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
                    "label",
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
                "sort": "start_ical asc",
                "limit": 10
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
                $entry['bezeichnung'] = $veranstaltung->paths->label->value;

                // Startzeit
                if ($veranstaltung->paths->start_ical->value) {
                    $dt = Carbon::parse($veranstaltung->paths->start_ical->value);
                    $entry['start_zeit'] = $dt->format('H:m');
                } else {
                    $entry['start_zeit'] = '';
                }

                // Endezeit
                if ($veranstaltung->paths->end_ical->value) {
                    $dt = Carbon::parse($veranstaltung->paths->end_ical->value);
                    $entry['ende_zeit'] = $dt->format('H:m');
                } else {
                    $entry['ende_zeit'] = '';
                }

                // Startdatum
                if ($veranstaltung->paths->start_ical->value) {
                    $hasDate = true;
                    $dt = Carbon::parse($veranstaltung->paths->start_ical->value);
                    $entry['start_ical'] = $veranstaltung->paths->start_ical->value;
                    $entry['first_course_date'] = '';
                    $entry['dateSort'] = $dt->getTimestamp();
                } else if ($veranstaltung->paths->first_course_date->value) {
                    $hasDate = true;
                    $dt = Carbon::parse($veranstaltung->paths->first_course_date->value);
                    $entry['start_ical'] = '';
                    $entry['first_course_date'] = $veranstaltung->paths->first_course_date->value;
                    $entry['dateSort'] = $dt->getTimestamp();
                } else {
                    $hasDate = false;
                    $entry['start_ical'] = '';
                    $entry['first_course_date'] = '';
                    $entry['dateSort'] = Carbon::now()->addCentury()->getTimestamp();
                }

                if ($hasDate) {
                    //$entry['day'] = $dt->day;
                    $entry['day'] = $dt->format('d');
                    $entry['month'] = $dt->locale('de')->shortMonthName;
                    $entry['year'] = $dt->year;
                } else {
                    $entry['day'] = null;
                    $entry['month'] = null;
                    $entry['year'] = null;
                }

                // hide if end date in the past, but still published, except for archive
                if ($entry['dateSort'] <= Carbon::now()->getTimestamp()) {
                    continue;
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
                }

                // Typ
                if ($veranstaltung->paths->relEvent_type->value) {
                    $entry['typ']['de'] = $veranstaltung->paths->relEvent_type->value->paths->label_de->value;
                    $entry['typ']['fr'] = $veranstaltung->paths->relEvent_type->value->paths->label_fr->value;
                    $entry['typ']['it'] = $veranstaltung->paths->relEvent_type->value->paths->label_it->value;
                    $entry['typ']['en'] = $veranstaltung->paths->relEvent_type->value->paths->label_en->value;
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
        usort($veranstaltungen, fn($a, $b) => $a['dateSort'] <=> $b['dateSort']);

        return $veranstaltungen;
    }

}