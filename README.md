# OpenAgenda php client

Librairie cliente basée sur le service [openagenda.com](http://openagenda.com).
- 0.1 : Simple wrapper d'appel
 
# Lecture d'événement(s)

## Requête

Instancier le reader

    $oa = new OpenAgenda\Reader($agendaId);
 
Récupérer tous les éléments avec pagination

    $response = $oa->getEvents([
        'page' => $page, // page courante
        'pagination' => [
            'range' => 10   // Paramètre de range pour la pagination
        ]
    ]);
    
Récupérer un événement suivant un id event

    $oa = new OpenAgenda\Reader($agendaId);

    if (!$event = $oa->getEvent($id)) {
        $app->abort(404, "Agenda $id does not exist.");
    }

## Réponse

Réponse de `getEvents()` 

    object(stdClass)#83 (5) {
        ["total"]=>
        int(3830)
        ["offset"]=>
        int(0)
        ["limit"]=>
        int(10)
        ["events"]=>
        array(10) {
        [0] => object(stdClass)#84 (39) {
            ["uid"]=>
            int(97500186)
            ["slug"]=> ....
          }
        [1] => ...
        }
        ["pagination"]=>
        array(13) {
              [0]=>
                    object(OpenAgenda\Pagination\Item)#586 (4) {
                    ["type":"OpenAgenda\Pagination\Item":private]=>
                    string(8) "previous"
                    ["isCurrent":"OpenAgenda\Pagination\Item":private]=>
                    bool(false)
                    ["number":"OpenAgenda\Pagination\Item":private]=>
                    int(0)
                    ["isclickable":"OpenAgenda\Pagination\Item":private]=>
                    bool(false)
                    }
              [1]=>
                    object(OpenAgenda\Pagination\Item)#587 (4) {
                      ["type":"OpenAgenda\Pagination\Item":private]=>
                      string(4) "page"
                      ["isCurrent":"OpenAgenda\Pagination\Item":private]=>
                      bool(true)
                      ["number":"OpenAgenda\Pagination\Item":private]=>
                      int(1)
                      ["isclickable":"OpenAgenda\Pagination\Item":private]=>
                      bool(false)
                    }
              [2]=>
                    object(OpenAgenda\Pagination\Item)#588 (4) {
                        ["type":"OpenAgenda\Pagination\Item":private]=>
                        string(4) "page"
                        ["isCurrent":"OpenAgenda\Pagination\Item":private]=>
                        bool(false)
                        ["number":"OpenAgenda\Pagination\Item":private]=>
                        int(2)
                        ["isclickable":"OpenAgenda\Pagination\Item":private]=>
                        bool(true)
                    }
       
    }
    
    
# Writer

## Introduction

La class Writer wrappe des method afin de pousser des informations dans la base OpenAgenda.
Attention l'API d'écriture va être refondu en Janvier 2017.

## Exemple d'utilisation du dans un app Silex pour faire un import

    $app->get('/{agendaId}/import', function($agendaId) use ($app, $aAgendas) {
    
        $aAgendaIds = implode(',', array_keys($aAgendas));
        
        // Read from Database
        $query = <<<EOT
            SELECT 
                e.uid AS event_uid, e.title AS event_title, e.abstract, e.description AS event_description, 
                e.oa_id AS event_oa_id,
                GROUP_CONCAT(d.date_start) AS ds, GROUP_CONCAT(d.date_end) AS de, 
                e.pid AS event_agenda_id,
                l.uid AS location_uid, l.oa_id AS location_oa_id, l.title AS location_title, 
                l.geo_latitude AS location_latitude, l.geo_longitude AS location_longitude,
                l.address AS location_address, l.zip AS location_zip, l.city AS location_city
            FROM tx_artificaagenda_event e
            LEFT JOIN tx_artificaagenda_date d ON d.event = e.uid
            LEFT JOIN tx_artificadirectories_recordings l ON l.uid = e.location
            WHERE e.pid IN ($aAgendaIds) AND e.uid = 6487
            AND e.oa_id IS NULL
            GROUP BY event_uid
            ORDER BY d.date_start DESC
            LIMIT 10
    EOT;
    
        $results = [];
        try {
            //connect as appropriate as above
            $stmt->bindValue('agendaIds', implode(',', array_keys($aAgendas)));
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $ex) {
            debug($ex->getMessage(),1);
        }
        
        $oaw = new OpenAgenda\Writer('your--secret--code');
    
        foreach ($results as $result) {
    
            // Location
            if (empty($result['location_oa_id'])) {
    
                $location = new OpenAgenda\Model\Location();
                $location->setId($result['location_oa_id']);
                $location->setPlaceName($result['location_title']);
                $location->setAddress(join(' ',[$result['location_address'],$result['location_zip'],$result['location_city']]));
                $location->setLongitude($result['location_longitude']);
                $location->setLatitude($result['location_latitude']);
    
                $location_o_id = $oaw->persist($location);
    
                $app['db']->query('UPDATE tx_artificadirectories_recordings SET oa_id = '.$location_o_id.' WHERE uid = '.$result['location_uid']);
    
            } else {
                $location_o_id = $result['location_oa_id'];
            }
    
            // Event
            if (empty($result['event_oa_id'])) {
                $event = new OpenAgenda\Model\Event();
    
                $event->setLocation($location_o_id);
                $event->setTitle($result['event_title']);
                $event->setHtml($result['event_description']);
                $event->setTags(['import201611']);
    
                // Set date to location
                $dss = explode(',', $result['ds']);
                $des = explode(',', $result['de']);
                foreach ($dss as $k => $v) {
                    $date = new OpenAgenda\Model\Date();
    
                    $ds = new \DateTime();
                    $de = new \DateTime();
                    $ds->setTimestamp($v);
                    $de->setTimestamp($des[$k]);
                    $date->setDate($ds->format('Y/m/d'));
                    $date->setTimeStart($ds->format('h:i'));
                    $date->setTimeEnd($de->format('h:i'));
                    $event->setDate($date);
                }
    
                $event_oa_id = $oaw->persist($event);
    
                $app['db']->query('UPDATE tx_artificaagenda_event SET oa_id = '.$event_oa_id.' WHERE uid = '.$result['event_uid']);
    
            } else {
                $event_oa_id = $result['event_oa_id'];
            }
    
            // Associate to 35710944 agenda uid
            $oaw->associate($event_oa_id,35710944);
    
        }
        
        return true;
    });
