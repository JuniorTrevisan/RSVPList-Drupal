<?php

namespace Drupal\rsvplist\Form;

use Drupal\Core\Form\ConfigFormBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class RSVPSettingsForm
 */
class RSVPSettingsForm extends ConfigFormBase {

    public function getFormID() {
        return 'rvsplist_admin_settings';
    }

    protected function getEditableConfigNames(){
        return [
            'rsvplist.settings'
            ];
    }

    public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL){
        $types = node_type_get_names();
        $config = $form['rsvplist_types'] = array( 
            '#type' => 'checkboxes',
            '#title' => $this-> t('The content types to enable RSVP collection'),
            '#default_value' => $config->get('allowed_types'),
            '#options' => $types,
            '#description' => t('On the specific node type'),
        );
        $form['array_filter'] = array('#type' => 'value', '#value' => TRUE);
        return parent::buildForm($form,$form_state);
    }

    public function submitForm(array &$form, FormStateInterface $form_state){
        $allowed_types = array_filter($form_state->getValue('rsvplist_types'));
        sort($allowed_types);
        $this->config('rsvplist.settings')
        ->set('allowed_types', $allowed_types)
        ->save();
        parent::submitForm($form,$form_state);
    }

}