<?php

/** 
*  Contains @ \Drupal\rsvplist\Controller\ReportController
*/

namespace Drupal\rsvplist\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

/**
 * Controller RSVP List Report
 */

 class ReportController extends ControllerBase {

    protected function load(){
        $select = Database::getConnnection()->select('rsvplist', 'r');
        $select->join('users_field_data', 'u', 'r.uid = i.uid');
        $select->join('node_field_data', 'n', 'r.nid = n.nid');
        $select->addField('u', 'name', 'username');
        $select->addField('n', 'title');
        $select->addField('r', 'mail');
        $entries = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
        return $entries;
    }

    public function report(){
        $content = array();
        $content['message'] = array(
            '#markup' => $this->t('List the all Events RSVPs'),
        );
        
        $headers = array(
            t('Name'),
            t('Event'),
            t('Mail'),
        );

        $rows = array();
        foreach($entries = $this->load() as $entry){
            $rows[] = array_map('Drupal\Component\Utility\SafeMarkup::checkPlain', $entry);
        }

        $content['table'] = array(
            '#type' => 'table',
            '#header' => $headers,
            '#rows' => $rows,
            '#empty' => t('No entries available'),
        );

        $content['#cache']['max-age'] = 0;
        return $content;
    }

 }