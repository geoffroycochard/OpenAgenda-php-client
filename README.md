# OpenAgenda php client

Librairie cliente basée sur le service [openagenda.com](http://openagenda.com).
0.1 : Simple wrapper d'appel
 
# Lecture d'événement(s)

## Requête

Instancier l'initiator

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
    
    
#