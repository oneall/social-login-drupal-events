<?php

namespace Drupal\social_login_events\EventSubscriber;

use Drupal\Core\Config\ConfigCrudEvent;
use Drupal\Core\Config\ConfigEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\user\Entity\User;

use Drupal\social_login\Event\SocialLoginUserLoginEvent;
use Drupal\social_login\Event\SocialLoginUserCreatedEvent;

/**
 * Reacts on Social Auth events.
 */
class SocialLoginSubscriber implements EventSubscriberInterface {

  // The event names to listen for, and the methods that should be executed.
  public static function getSubscribedEvents() {
    $events[SocialLoginUserLoginEvent::EVENT_NAME] = ['onUserLogin'];
    $events[SocialLoginUserCreatedEvent::EVENT_NAME] = ['onUserCreated'];
    return $events;
  }

  // Triggered whenever a new user is being created.
  public function onUserCreated(SocialLoginUserCreatedEvent $event)
  {
	// User account.
	$account = $event->get_account();

	// Social Network Profile Data.
	$data = $event->get_social_network_profile_data();

        // Check identity.
	if (is_array($data) && isset($data['user']['identity']))
	{
	    // Extract identity.
            $identity = $data['user']['identity'];

            // Extract firstname.
            $first_name = (!empty($identity['name']['givenName']) ? trim ($identity['name']['givenName']) : '');

            // Extract lastname.
            $last_name = (!empty($identity['name']['familyName']) ? trim ($identity['name']['familyName']) : '');

            // Update.	
            if ($account->hasField('field_first_name'))
	    {
 	    	$account->set('field_first_name', $first_name);
            }

	    if ($account->hasField('field_last_name'))
	    {	
            	$account->set('field_last_name', $last_name);
            }		
	    
	    if ($account->hasField('field_full_name'))
            {
            	$account->set('field_full_name',  trim($first_name.' '.$last_name));
            }

            $account->save();
	}
  }

  // Triggered whenever a user logs in.
  public function onUserLogin(SocialLoginUserLoginEvent $event)
  {
	// User account.
	$account = $event->get_account();

	// Social Network Profile Data.
	$data = $event->get_social_network_profile_data();

        // Check identity.
	if (is_array($data) && isset($data['user']['identity']))
	{
		// CUSTOM PROCESSING
        }
  }
}
