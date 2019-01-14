<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * 
 * Codeigniter v3
 * Library for eMailPlatform API
 * 
 * Documenation for eMailPlatform API/SDK can be found here: https://emailplatform.com/api/php/
 * 
 * 
 */
class Emailplatform {

    public $URL = 'https://api.mailmailmail.net/v1.1'; // api version url
    var $settings = array();

    /**
     * Class constructor
     *
     * Define username and token for eMailPlatform API access
     * 
     */
    public function __construct($settings = array()) {
        $this->settings = $settings;
    }

    private function GetHTTPHeader() {
        switch ($this->settings["format"]) {
            case "xml":
                return array(
                    "Accept: application/xml; charset=utf-8",
                    "ApiUsername: " . $this->settings['username'],
                    "ApiToken: " . $this->settings['token']
                );
                break;
            case "serialized":
                return array(
                    "Accept: application/vnd.php.serialized; charset=utf-8",
                    "ApiUsername: " . $this->settings['username'],
                    "ApiToken: " . $this->settings['token']
                );
                break;
            case "php":
                return array(
                    "Accept: application/vnd.php; charset=utf-8",
                    "ApiUsername: " . $this->settings['username'],
                    "ApiToken: " . $this->settings['token']
                );
                break;
            case "csv":
                return array(
                    "Accept: application/csv; charset=utf-8",
                    "ApiUsername: " . $this->settings['username'],
                    "ApiToken: " . $this->settings['token']
                );
                break;
            default:
                return array(
                    "Accept: application/json; charset=utf-8",
                    "ApiUsername: " . $this->settings['username'],
                    "ApiToken: " . $this->settings['token']
                );
                break;
        }
    }

    private function DecodeResult($input = '') {
        switch ($this->settings["format"]) {
            case "xml":
                // @todo implement parser
                return $input;
                break;
            case "serialized":
                // @todo implement parser
                return $input;
                break;
            case "php":
                // @todo implement parser
                return $input;
                break;
            case "csv":
                // @todo implement parser
                return $input;
                break;
            default:
                return json_decode($input, TRUE);
                break;
        }
    }

    // Make API Get request
    private function MakeGetRequest($url = "", $fields = array()) {
        // open connection
        $ch = curl_init();
        if (!empty($fields)) {
            $url .= "?" . http_build_query($fields, '', '&');
        }

        // set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->GetHTTPHeader());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // disable for security
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        // execute post
        $result = curl_exec($ch);

        // close connection
        curl_close($ch);
        return $this->DecodeResult($result);
    }

    // Make API Post request
    private function MakePostRequest($url = "", $fields = array()) {
        try {
            // open connection
            $ch = curl_init();

            // add the setting to the fields
            $encodedData = http_build_query($fields, '', '&');

            // set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->GetHTTPHeader());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
            // disable for security
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

            // execute post
            $result = curl_exec($ch);

            // close connection
            curl_close($ch);
            return $this->DecodeResult($result);
        } catch (Exception $error) {
            return $error->GetMessage();
        }
    }

    // Make API Delete request
    private function MakeDeleteRequest($url = "", $fields = array()) {
        // open connection
        $ch = curl_init();
        $encodedData = http_build_query($fields, '', '&');

        // set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->GetHTTPHeader());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // disable for security
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        // execute post
        $result = curl_exec($ch);

        // close connection
        curl_close($ch);
        return $this->DecodeResult($result);
    }

    // Function to check system requirements
    public function HasRequirements() {
        if (!is_callable('curl_init')) {
            return 'curl is not installed correctly!';
        }

        $params = array(
            "test" => "a"
        );
        $url = $this->URL . "/Test";

        $result = $this->MakePostRequest($url, $params);
        if (!array_key_exists('postResponse', $result)) {
            return 'Post request not work properly';
        }

        $result = $this->MakeDeleteRequest($url, $params);
        if (!array_key_exists('deleteResponse', $result)) {
            return 'Delete request not work properly';
        }
        return 'All requirements work correctly';
    }

    // Test api user auth
    public function TestUserToken() {
        $url = $this->URL . "/Test/TestUserToken";
        return $this->MakePostRequest($url);
    }

    /**
     * IsSubscriberOnList
     * Checks whether a subscriber is on a particular list based on their email
     * address/mobile or subscriberid and whether you are checking only for active
     * subscribers.
     *
     * @param Array $listids
     *        	Lists to check on. If this is not an array, it's turned in to
     *        	one for easy checking.
     * @param String $emailaddress
     *        	Email address to check for.
     * @param String $mobile
     *        	Mobile phone to check for.
     * @param String $mobilePrefix
     * 			Country calling code.
     * @param Int $subscriberid
     *        	Subscriber id. This can be used instead of the email address.
     * @param Boolean $activeonly
     *        	Whether to only check for active subscribers or not. By
     *        	default this is false - so it will not restrict searching.
     * @param Boolean $not_bounced
     *        	Whether to only check for non-bounced subscribers or not. By
     *        	default this is false - so it will not restrict searching.
     * @param Boolean $return_listid
     *        	Whether to return the listid as well as the subscriber id. By
     *        	default this is false, so it will only return the
     *        	subscriberid. The bounce processing functions changes this to
     *        	true, so it returns the list and the subscriber id's.
     * @return Int|False Returns false if there is no such subscriber. Otherwise
     *         returns the subscriber id.
     */
    public function IsSubscriberOnList($params = array()) {

        $url = $this->URL . "/Subscribers/IsSubscriberOnList";
        return $this->MakeGetRequest($url, $params);
    }

    /**
     * IsUnSubscriber
     * Checks whether an email address is an 'unsubscriber' - they have
     * unsubscribed from a list.
     *
     * @param Int $listid
     *        	List to check for.
     * @param String $emailaddress
     *        	Email Address to check.
     * @param String $mobile
     * 			Mobile number to check.
     * @param String $mobilePrefix
     * 			Country calling code.
     * @param Int $subscriberid
     *        	Subscriber id to check.
     * @param String $service
     * 			Whether to check from email campaigns or sms campaigns.
     * @return Int|False Returns the unsubscribed id if there is one. Returns
     *         false if there isn't one.
     */
    public function IsUnsubscriber($params = array()) {
        $url = $this->URL . '/Subscribers/IsUnSubscriber';
        return $this->MakeGetRequest($url, $params);
    }

    /**
     * Load
     * Loads up the customfield and sets the appropriate class variables.
     * This handles loading of a subclass with different options and settings.
     *
     * @param Int $fieldid
     *        	The fieldid to load up. If the field is not present then it
     *        	will not load up.
     * @param Boolean $return_options
     *        	Whether to return the information loaded from the database or
     *        	not. The default is not to return the options, so this sets up
     *        	the class variables instead. Only subscriber importing should
     *        	need to return the options.
     *
     * @return Boolean Will return false if the fieldid is not present, or the
     *         field can't be found, otherwise it set the class vars,
     *         associations and options and return true.
     */
    public function LoadCustomField($params = array()) {
        $url = $this->URL . "/CustomFields/LoadCustomField";
        return $this->MakeGetRequest($url, $params);
    }

    /**
     * UnsubscribeSubscriberEmail
     * Unsubscribes an email address from a particular list.
     *
     * @param Int $listid
     *        	List to remove subscriber from.
     * @param String $emailaddress
     *        	Subscriber's email address to unsubscribe.
     * @param Int $subscriberid
     *        	Subscriberid to remove.
     * @param Boolean $skipcheck
     *        	Whether to skip the check to make sure they are on the list.
     * @param Int $statid
     *        	The statistics id we're updating so we can see (through stats)
     *        	the number of people who have unsubscribed directly from a
     *        	send.
     * @return Array Returns a status (success,failure) and a reason why.
     */
    public function UnsubscribeSubscriberEmail($params = array()) {
        $url = $this->URL . '/Subscribers/UnsubscribeSubscriberEmail';
        return $this->MakePostRequest($url, $params);
    }

    /**
     * UnsubscribeSubscriberMobile
     * Unsubscribes a mobile phone from a particular list.
     *
     * @param Int $listid
     *        	List to remove them from.
     * @param String $mobile
     *        	Subscriber's mobile phone to unsubscribe.
     * @param String $mobilePrefix
     * 			Country calling code.
     * @param Int $subscriberid
     *        	Subscriberid to remove.
     * @param Boolean $skipcheck
     *        	Whether to skip the check to make sure they are on the list.
     * @param Int $statid
     *        	The statistics id we're updating so we can see (through stats)
     *        	the number of people who have unsubscribed directly from a
     *        	send.
     * @return Array Returns a status (success,failure) and a reason why.
     */
    public function UnsubscribeSubscriberMobile($params = array()) {
        $url = $this->URL . '/Subscribers/UnsubscribeSubscriberMobile';
        return $this->MakePostRequest($url, $params);
    }

    /**
     * AddSubscriberToList
     * Adds a subscriber to a list.
     * Checks whether the list actually exists. If it doesn't, returns an error.
     *
     * @param Int $listid
     *        	The list to add the subcriber to.
     * @param String $emailaddress
     *        	Subscriber address to add to the list.
     * @param String $mobile
     *        	Subscriber mobile phone to add to list.
     * @param String $mobilePrefix
     * 			Subscriber country calling code.
     * @param Array $contactFields
     * 			Subscribers' contact fields.
     * @param Boolean $add_to_autoresponders
     *        	Whether to add the subscriber to the lists' autoresponders or
     *        	not.
     * @param Boolean $skip_listcheck
     *        	Whether to skip checking the list or not. This is useful if
     *        	you've already processed the lists to make sure they are ok.
     *
     * @return Boolean Returns false if there is an invalid subscriber or list
     *         id, or if the list doesn't really exist. If it works, then it
     *         returns the new subscriber id from the database.
     */
    public function AddSubscriberToList($params = array()) {
        $url = $this->URL . '/Subscribers/AddSubscriberToList';
        return $this->MakePostRequest($url, $params);
    }

    /**
     * GetLists
     * Gets a list of lists that this user owns / has access to.
     *
     *
     * @return Array Returns an array - list of listid's this user has created
     *         (or if the user is an admin/listadmin, returns everything).
     */
    public function GetLists() {
        $url = $this->URL . '/Users/GetLists';
        $params = array();
        return $this->MakeGetRequest($url, $params);
    }

    /**
     * CreateList
     * This function creates a list based on the current class vars.
     *
     * @param String $listName
     * 			Name of the list.
     * @param String $descriptiveName
     * 			List description.
     * @param String $mobile_prefix
     * 			Default country calling code.
     * @param Array $contact_fields
     * 			Comma separated list of contact fields id related to this contact list.
     * 
     * @return Boolean Returns true if it worked, false if it fails.
     */
    public function CreateList($params = array()) {
        $url = $this->URL . '/Lists/CreateList';
        return $this->MakePostRequest($url, $params);
    }

    /**
     * UpdateList
     * Updates a current list based on the current class vars.
     * @param Int @listid
     * 			ID of list which you want to edit.
     * @param String $listName
     * 			New name of the list.
     * @param String $descriptiveName
     * 			New list description.
     * @param String $mobile_prefix
     * 			New country calling code.
     *
     * @return Boolean Returns true if it worked, false if it fails.
     */
    public function UpdateList($params = array()) {
        $url = $this->URL . '/Lists/UpdateList';
        return $this->MakePostRequest($url, $params);
    }

    /**
     * DeleteList
     * Delete a list from the database.
     *
     * @param Int $listid
     *        	Listid of the list to delete. If not passed in, it will delete
     *        	'this' list.
     *
     * @return Boolean True if it deleted the list, false otherwise.
     */
    public function DeleteList($params) {
        $url = $this->URL . '/Lists/DeleteList';
        return $this->MakeDeleteRequest($url, $params);
    }

    /**
     * CreateCustomField
     * Create new custom field
     *
     * @param string $name name of custom field.
     * @param string $fieldtype type of custom field.
     * @param Array $fieldsettings settings for custom field.
     *
     * @return int id of new custom field.
     */
    public function CreateCustomField($params = array()) {
        $url = $this->URL . '/CustomFields/CreateCustomField';
        return $this->MakePostRequest($url, $params);
    }

    /**
     * GetCustomFields
     * Fetches custom fields for the list(s) specified.
     *
     * @param Array $listids
     *        	An array of listids to get custom fields for. If not passed
     *        	in, it will use 'this' list. If it's not an array, it will be
     *        	converted to one.
     *          
     * @return Array Custom field information for the list provided.
     */
    public function GetCustomFields($params = array()) {
        $url = $this->URL . '/Lists/GetCustomFields';
        return $this->MakeGetRequest($url, $params);
    }

    /**
     * SendNewsletter
     * 		Attempts to send a newsletter to specific subscriber or seeks for subscriber with given email address
     * @param number $newsletterid the ID of the newsletter that will be sent
     * @param number $subscriberid [optional] recipient subscriber's ID, $subscriberid or $email required
     * @param string $email [optional] address used to found recipient from posible recipients of the newsletter, $subscriberid or $email required
     * @param string $senderEmail [optional] sender email from which the email will appear to be sent
     * @param string $senderName [optional] sender name from which the email will appear to be sent
     * @param string $replyEmail [optional] reply to email, replying will be use this email
     * @return boolean True if newsletter was sent, False otherwise
     */
    public function SendNewsletter($params = array()) {
        $url = $this->URL . '/Messaging/SendNewsletter';
        return $this->MakePostRequest($url, $params);
    }

    /**
     * GetSubscribers
     * Returns a list of subscriber id's based on the information passed in.
     * 
     * @param Array $searchinfo
     *        	An array of search information to restrict searching to. This
     *        	is used to construct queries to cut down the subscribers
     *        	found.
     * @param Boolean $countonly
     * 			Whether to only do a count or get the list of subscribers as well.
     * 
     * @return Mixed This will return the count only if that is set to true.
     *         Otherwise this will return an array of data including the count
     *         and the subscriber list.Or returns boolean if $atleastone is set
     *         to true
     */
    public function GetSubscribers($params = array()) {
        $url = $this->URL . '/Subscribers/GetSubscribers';
        return $this->MakeGetRequest($url, $params);
    }

    /**
     * GetSubscriberDetails
     * Gets subscriber data including all related events and bounces.
     *
     * @param Integer $listid
     * 			Contact list you are searching on.
     * @param Integer $subscriberid
     * 			ID of the subscriber you want to get more details.
     * @param String $emailaddress
     *        	Email address of the subscriber you want to get more details.
     * @param String $mobile
     * 			Mobile number the subscriber you want to get more details.
     * @param String $mobile_prefix
     * 			Country calling code.
     * 
     * @return Array Return an array of subscribers details.
     */
    public function GetSubscriberDetails($params = array()) {
        $url = $this->URL . '/Subscribers/GetSubscriberDetails';
        return $this->MakeGetRequest($url, $params);
    }

    /**
     * GetSubscribeEvents
     * Gets subscriber data including all related events and bounces.
     *
     * @param Integer $listid
     * 			Contact list you are searching on.
     * @param Integer $subscriberid
     * 			ID of the subscriber you want to get more details.
     * 
     * @return Array Return an array of subscriber events.
     */
    public function GetSubscriberEvents($params = array()) {
        $url = $this->URL . '/Subscribers/GetSubscriberEvents';
        return $this->MakeGetRequest($url, $params);
    }

    /**
     * Save Subscriber CustomFields
     * Saves custom field information for a particular subscriber, particular
     * list and particular field.
     * NOTE:
     * - Any old custom field data will be deleted.
     * - NULL data values will not be saved to the database.
     *
     * @param Integer $subscriberid
     *        	ID of the subscriber whose data need to be updated.
     * @param Integer $fieldid
     *        	The ID of contact field you are saving for.
     * @param Mixed $value
     *        	The actual custom field value. If this is an array, it will be
     *        	serialized up before saving.
     * @param $skipEmptyData
     * 			Method won't be executed if field value is empty.
     * 
     * @return Boolean Returns TRUE if successful, FALSE otherwise.
     */
    public function SaveSubscriberCustomField($params = array()) {
        $url = $this->URL . '/Subscribers/SaveSubscriberCustomField';
        return $this->MakePostRequest($url, $params);
    }

    /**
     * Save Subscriber CustomFields By List search
     * Saves custom field information for a searched subscriber, particular
     * list and particular fieldsearch.
     * NOTE:
     * - Any old custom field data will be deleted.
     * - NULL data values will not be saved to the database.
     *
     * @param Integer $listid
     *        	ID of the list where data must be searched for.
     * @param Integer $fieldid
     *        	The ID of contact field you are saving for.
     * @param Mixed $data
     *        	The actual custom field data you want to insert. If this is an array, it will be
     *        	serialized up before saving.
     * @param $searchinfo
     * 			data to search for within custom field
     * 
     * @return Boolean Returns TRUE if successful, FALSE otherwise.
     */
    public function SaveSubscriberCustomFieldByList($params = array()) {
        $url = $this->URL . '/Subscribers/SaveSubscriberCustomFieldByList';
        return $this->MakePostRequest($url, $params);
    }

    /**
     * LoadSubscriberListCustomFields
     * Loads customfield data based on the list specified.
     *
     * @param Int $subscriberid
     *        	Subscriber to load up.
     * @param Int $listid
     *        	The list the subscriber is on.
     *        
     * @return Array Returns the subscribers custom field data for that
     *         particular list.
     */
    public function LoadSubscriberCustomFields($params = array()) {
        $url = $this->URL . '/Subscribers/LoadSubscriberCustomFields';
        return $this->MakeGetRequest($url, $params);
    }

    /**
     * DeleteSubscriber
     * Deletes a subscriber and their information from a particular list.
     *
     * @param Integer $listid
     *        	List to delete them off.
     * @param String $emailaddress
     *        	Email Address to delete.
     * @param String $mobile
     * 			Mobile to delete.
     * @param String $mobilePrefix
     * 			Country calling code.
     * @param Integer $subscriberid
     *        	Subscriberid to delete.
     *
     * @return Array Returns a status (success,failure) and a reason why.
     */
    public function DeleteSubscriber($params = array()) {
        $url = $this->URL . '/Subscribers/DeleteSubscriber';
        return $this->MakeDeleteRequest($url, $params);
    }

    /**
     * UpdateSubscriber
     * Updates subscriber info.
     *
     * @param Integer $listid
     * 			List from which the subscriber will be updated.
     * @param Integer $subscriberid
     * 			Subscriberid to update.
     * @param String $emailaddress
     * 			Email address of the subscriber you want update.
     * @param String $mobile
     * 			Mobile of the subscriber you want update.
     * @param String $mobilePrefix
     * 			Country calling code. 
     * @param Array $customfields
     *        	Contact fields to be updated.
     *        
     * @return Array Returns a status (success,failure) and a reason why.
     */
    public function UpdateSubscriber($params = array()) {
        $url = $this->URL . '/Subscribers/UpdateSubscriber';
        return $this->MakePostRequest($url, $params);
    }

    /**
     * ChangeMobile
     * Change subscriber mobile.
     *
     * @param Integer $listid
     * 			List from which the subscriber will be updated.
     * @param Integer $subscriberid
     * 			Subscriberid to update.
     * @param String $mobile
     * 			Mobile of the subscriber you want update.
     * @param String $mobilePrefix
     * 			Country calling code.
     * @return Array Returns a status (success,failure) and a reason why.
     */
    public function ChangeMobile($params = array()) {
        $url = $this->URL . '/Subscribers/ChangeMobile';
        return $this->MakePostRequest($url, $params);
    }

    /**
     * RequestUpdateEmail
     * Request to change current email address.
     *
     *
     * @param Integer $subscriberid
     * 			Subscriberid to update.
     * @param Integer $listid
     * 			List from which the subscriber will be updated.
     * @param String $oldemail
     * 			Current email address.
     * @param String $newemail
     * 			New email address.
     * @param Array $contactFields
     *        	Contact fields to be updated.
     *
     * @return Integer Returns a status (true/false).
     */
    public function RequestUpdateEmail($params = array()) {
        $url = $this->URL . '/Subscribers/RequestUpdateEmail';
        return $this->MakePostRequest($url, $params);
    }

    /**
     * FetchStats
     * Fetches the details of a newsletter or autoresponder statistics entry
     *
     * @param Integer $statid
     *        	The statid of the entry you want to retrieve from the
     *        	database.
     * @param String $statstype
     *        	The type of statistics the entry you are retrieving is
     *        	(newsletter / autoresponder)
     *
     * @return Array Returns an array of details about the statistics entry
     */
    public function FetchStats($params = array()) {
        $url = $this->URL . '/Stats/FetchStats';
        return $this->MakeGetRequest($url, $params);
    }

    /**
     * GetBouncesByList
     * Fetches a list of bounced emails.
     *
     * @param Integer $listid
     *        	Id of a list from which the results are fetched.
     * @param Boolean $count_only
     * 			Whether to return the number of bounces instead of a list of bounces.
     * @param String $bounce_type
     * 			The type of bounce to get results for ("soft","hard","any").
     * @param String $searchType
     * 			Which search rule should be used in date search. 
     * 			Possible values: before, after, between, not, exact/exactly.
     * @param String $searchStartDate
     * 			Date for filtering.
     * @param String $searchEndDate
     * 			Date for filtering.
     * 
     * @return Array Returns an array of details about the statistics entry
     */
    public function GetBouncesByList($params = array()) {
        $url = $this->URL . '/Stats/GetBouncesByList';
        return $this->MakeGetRequest($url, $params);
    }

    /**
     * GetUnsubscribesByList
     * Fetches a list of unsubscribed emails.
     *
     * @param Integer $listid
     *        	Id of a list from which the results are fetched.
     * @param Boolean $count_only
     * 			Whether to return the number of bounces instead of a list of bounces.
     * @param String $searchType
     * 			Which search rule should be used in date search.
     * 			Possible values: before, after, between, not, exact/exactly.
     * @param String $searchStartDate
     * 			Date for filtering.
     * @param String $searchEndDate
     * 			Date for filtering.
     * 
     * @return Array Returns an array of details about the statistics entry
     */
    public function GetUnsubscribesByList($params = array()) {
        $url = $this->URL . '/Stats/GetUnsubscribesByList';
        return $this->MakeGetRequest($url, $params);
    }

    /**
     * GetOpens
     * Fetches a list of subscribers who opened a campaign or autoresponder.
     *
     * @param Integer $statid
     *        	The statids you want to fetch data for.
     * @param Boolean $count_only
     *        	Specify True to return the number of opens instead of a list
     *        	of opens.
     * @param Int $only_unique
     *        	Specify true to count/retrieve unique opens only, specify
     *        	false for all opens.
     *        
     * @return Array Returns an array of opens or if $count_only was set to true
     *         returns the number of opens in total
     */
    public function GetOpens($params = array()) {
        $url = $this->URL . '/Stats/GetOpens';
        return $this->MakeGetRequest($url, $params);
    }

    /**
     * GetRecipients
     * Fetches a list of recipients for an autoresponder.
     *
     * @param Integer $statid
     *        	The statid you want to fetch data for.
     * @param Boolean $count_only
     *        	Specify True to return the number of recipients instead of a
     *        	list of recipients.
     *        
     * @return Array Returns an array of recipients or if $count_only was set to
     *         true returns the number of recipients in total
     */
    public function GetRecipients($params = array()) {
        $url = $this->URL . '/Stats/GetRecipients';
        return $this->MakeGetRequest($url, $params);
    }

    /**
     * CopyNewsletter
     * Copy a newsletter along with attachments, images etc.
     *
     * @param Int $oldid
     *        	Newsletterid of the newsletter to copy.
     * @param String $name
     * 			Name of the copied newsletter.
     * @param String $subject
     * 			Subject of the copied newsletter.
     *
     * @return Array Returns an array of statuses. The first one is whether the
     *         newsletter could be found/loaded/copied, the second is whether
     *         the images/attachments could be copied. Both are true for
     *         success, false for failure.
     */
    public function CopyNewsletter($params = array()) {
        $url = $this->URL . '/Newsletters/CopyNewsletter';
        return $this->MakePostRequest($url, $params);
    }

    /**
     * GetNewsletters
     * Get a list of NewsletterDB based on the criteria passed in.
     *
     * @param Boolean $countonly
     *        	Whether to only get a count of lists, rather than the
     *        	information.
     * @param Boolean $getLastSentDetails
     * 			Get info about the last sent details.
     * @param Boolean $content
     * 			Whether to show campaign content or not.
     * @param String $aftercreatedate
     * 			Get newsletters created after this date.
     * @param String $newsletterNameLike
     * 			Get newsletters with name like this.
     *
     * @return Mixed Returns false if it couldn't retrieve newsletter
     *         information. Otherwise returns the count (if specified), or an
     *         array of NewsletterDB.
     */
    public function GetNewsletters($params = array()) {
        $url = $this->URL . '/Newsletters/GetNewsletters';
        return $this->MakeGetRequest($url, $params);
    }

    /**
     * GetAllListsForEmailAddress
     * Gets all subscriberid's, listid's for a particular email address and
     * returns an array of them.
     *
     * @param String $emailaddress
     *        	The email address to find on all of the lists.
     * @param Array $listids
     *        	The lists to check for the address on.
     * @param Int $main_listid
     *        	This is used for ordering the results of the query. When this
     *        	is passed in, the main list should appear at the top.
     * @param Boolean $activeonly
     * 			Whether to only check for active subscribers or not.
     * @param Boolean $include_deleted
     *        	Whether to get the subscribers that are marked as deleted.
     *        
     * @return Array Returns either an empty array (if no email address is
     *         passed in) or a multidimensional array containing both
     *         subscriberid and listid.
     */
    public function GetAllListsForEmailAddress($params = array()) {
        $url = $this->URL . '/Subscribers/GetAllListsForEmailAddress';
        return $this->MakeGetRequest($url, $params);
    }

    /**
     * CopyList
     * Copy list details only along with custom field associations.
     *
     * @param Integer $listid
     *        	Listid to copy.
     *        
     * @return Array Returns an array of status (whether the copy worked or not)
     *         and a message to go with it. If the copy worked, then the message
     *         is 'false'.
     */
    public function CopyList($params = array()) {
        $url = $this->URL . '/Lists/CopyList';
        return $this->MakePostRequest($url, $params);
    }

    /**
     * ScheduleSendNewsletter
     * Schedule newsletter campaign for sending.
     *
     * @param Integer $campaignid
     *        	ID of the campain which need to be scheduled.
     * @param Float $hours
     * 			When should the campaign start
     * 			(In how many hours from a starting point(real time : now)).
     * 
     * @return Array Returns an array of status (whether the copy worked or not)
     *         and a message to go with it. If the copy worked, then the message
     *         is 'false'.
     */
    public function ScheduleSendNewsletter($params = array()) {
        $url = $this->URL . '/Sends/ScheduleSend';
        return $this->MakePostRequest($url, $params);
    }

    /**
     * ScheduleSendSMS
     * Schedule SMS campaign for sending.
     *
     * @param Integer $campaignid
     *        	ID of the campain which need to be scheduled.
     * @param Float $hours
     * 			When should the campaign start
     * 			(In how many hours from a starting point(real time : now)).
     * @param Array $lists
     * 			Which lists to send.
     * 
     * @return Array Returns an array of status (whether the copy worked or not)
     *         and a message to go with it. If the copy worked, then the message
     *         is 'false'.
     */
    public function ScheduleSendSMS($params = array()) {
        $url = $this->URL . '/SMSSends/ScheduleSend';
        return $this->MakePostRequest($url, $params);
    }

    public function GetLatestStats($params = array()) {
        $url = $this->URL . '/Stats/GetLatestStats';
        return $this->MakeGetRequest($url, $params);
    }

    public function GetOneToManySubscriberData($params = array()) {
        $url = $this->URL . '/CustomFields/GetOneToManySubscriberData';
        return $this->MakeGetRequest($url, $params);
    }

    /**
     * GetListSummary
     * Calculates the total number of emails sent, bounces, unsubscribes, opens,
     * forwards and link clicks for a list
     *
     * @param Int $listid
     *        	The stat id of the entry you want to retrieve from the database.
     *        
     * @return Array Returns an array of the statistics
    **/
    public function GetListSummary($params = array()) {
        $url = $this->URL . '/Stats/GetListSummary';
        return $this->MakeGetRequest($url, $params);
    }

    public function GetSubscribersUpdatedSince($params = array()) {
        $url = $this->URL . '/Subscribers/GetSubscribersUpdatedSince';
        return $this->MakeGetRequest($url, $params);
    }

    public function GetSampleDataForOTM($params = array()) {
        $url = $this->URL . '/Subscribers/GetSampleDataForOTM';
        return $this->MakeGetRequest($url, $params);
    }

    public function GetSubscribersFromSegment($params = array()) {
        $url = $this->URL . '/Subscribers/GetSubscribersFromSegment';
        return $this->MakeGetRequest($url, $params);
    }

    public function ViewNewsletter($params = array()) {
        $url = $this->URL . '/Newsletters/ViewNewsletter';
        return $this->MakeGetRequest($url, $params);
    }

    public function GetTriggersForSegment($params = array()) {
        $url = $this->URL . '/Segments/GetTriggersForSegment';
        return $this->MakeGetRequest($url, $params);
    }

    public function SendSMS($params = array()) {
        $url = $this->URL . '/SMS/Send';
        return $this->MakePostRequest($url, $params);
    }

}
