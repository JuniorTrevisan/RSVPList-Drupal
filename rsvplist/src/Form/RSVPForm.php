<?php
/**
 * @file Contains \Drupal\rsvplist\Form\RSVPForm
 */

namespace Drupal\rsvplist\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

    /**
     * Provides an RSVP form.
     */
    class RSVPForm extends FormBase {  

        public function getFormId() {
            return 'rsvplist_email_form';
        }

        public function buildForm(array $form, FormStateInterface $form_state){
            $node = \Drupal::routeMatch()->getParameter('node');        
            //$nid = $node->nid->value; 
            //adaptação para funcionar o código conforme forum drupal.org
            if ($node instanceof \Drupal\node\NodeInterface) {                
                $nid = $node->id();
            }  else {
                $nid = 0;
            }         
            $form['email'] = array(
                '#title' => t('Email Address'),
                '#type' => 'textfield',
                '#size' => 25,
                '#description' => t('Weĺl send updates to the email address you provide'),
                '#required' => TRUE,
            );
            $form['submit'] = array (
                '#type' => 'submit',
                '#value' => t('RSVP'),
            );
            $form['nid'] = array (
                '#type' => 'hidden',
                '#value' => $nid,
            );
            return $form;
        }

        public function validateForm(array &$form, FormStateInterface $form_state){
            $value = $form_state->getvalue('email');
            if($value == !\Drupal::service('email.validator')->isValid($value)){
                $form_state->setErrorByName('email', t('The email address %mail is not valid', array('%mail' => $value)));
                return;
            }
            $node = \Drupal::routeMatch()->getParameter('node');
            //adaptação para funcionar o código conforme forum drupal.org
            if ($node instanceof \Drupal\node\NodeInterface) {                
                $nid = $node->id();
            } else {
                $nid = 0;
            }
            $select = Database::getConnection()->select('rsvplist', 'r');
            $select->fields('r',array('nid'));
            $select->condition('nid', $nid);
            $select->condition('mail', $value);            
            $results = $select->execute();
            if(!empty($results->fetchCol())){
                $form_state->setErrorByName('email', t('The address %mail is already subscribed', array('%mail' => $value)));
            }
        }

        public function submitForm(array &$form, FormStateInterface $form_state){
            //drupal_set_message(t('The form is ok!'));
            $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
            db_insert('rsvplist')
            ->fields(array(
                'mail' => $form_state->getValue('email'),
                'nid' => $form_state->getValue('nid'),
                'uid' => $user->id(),
                'create' => time(),
            ))
           ->execute();          
            drupal_set_message(t('Thank you for your rsvp, you are on the list for event'));
        }
        
    }