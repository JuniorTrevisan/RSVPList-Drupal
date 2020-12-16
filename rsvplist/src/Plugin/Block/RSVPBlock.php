<?php

namespace Drupal\rsvplist\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

class RSVPBlock extends BlockBase {

    /**
     * * Provides a 'RSVP' List Block
     *  @Block(
     *      id = "rsvp_block", 
     *      admin_label =  @Translation("RSVP Block"),
     *      category = @Translation("Blocks")
     *  )
     */

    public function build() {       
       return \Drupal::formBuilder()->getForm('Drupal\rsvplist\Form\RSVPForm');       
    }

    public function blockAccess(AccountInterface $account){
        $node = \Drupal::routeMatch()->getParameter('node');
        $nid = $node->nid->value;
        if(is_numeric($nid)){
            return AccessResult::allowedIfHasPermission($account, 'view rsvplist');
        }
        return AccessResult::forbidden();
    }

} 
