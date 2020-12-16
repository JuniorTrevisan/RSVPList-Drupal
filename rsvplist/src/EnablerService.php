<?php

/**
 * Enabler Service
 */

namespace Drupal\rsvplist;

use Drupal\Core\Database\Database;
use Drupal\node\Entity\Node;

class EnablerService {

    public function __construct(){

    }

    public function setEnabled(Node $node){
        if(!$this->isEnabled($node)){
            $insert = Database::getConnection()->insert('rsvplist_enabled');
            $insert->fields(array('nid'),array($node->id()));
            $insert->execute();
        }
    }

    public function isEnabled(Node $node){
        if($node->isNew()){
            return FALSE;
        }
        $select = Database::getConnection()->select('rsvplist_enabled', 're');
        $select->fields('re',array($nid));
        $select->condition('nid', $node->id());
        $results = $select->execute();
        return !empty($results->fetchCol());
    }

    public function delEnabled(Node $node){        
        $delete = Database::getConnection()->select('rsvplist_enabled');       
        $delete->condition('nid', $node->id());
        $delete->execute();        
    }


}