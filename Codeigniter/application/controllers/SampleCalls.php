<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SampleCalls extends CI_Controller {

    /**
     * 
     *  This controller includes Sample functions for use of eMailPlatform API
     * 
     */
    public function __construct() {
        parent::__construct();

        $settings['username'] = 'api_username';
        $settings['token'] = 'api_usertoken';
        $settings['format'] = 'json';
        $this->load->library('emailplatform', $settings);
    }

    public function index() {
        $result = $this->emailplatform->TestUserToken();
        var_dump($result);
    }

    /*
     * 
     * List functions
     * 
     */

    public function GetLists() {

        /*
         * 
         * @return = array of lists
         * 
         */

        $result = $this->emailplatform->GetLists();
        var_dump($result);
    }

    public function CreateList() {

        /*
         * 
         * @return = int(listid) / array(bool, message)
         * 
         */

        $params = array(
            'listName' => 'CreateList', // listName is required
            'descriptiveName' => 'This list was created by the API', // default = false
            'mobile_prefix' => 45, // default = false
            'contact_fields' => array(2, 3, 8245) // default = false
        );

        $result = $this->emailplatform->CreateList($params);
        var_dump($result);
    }

    public function UpdateList() {

        /*
         * 
         * @return = bool(true) / string(message) / array(bool, message)
         * 
         */

        $params = array(
            'listid' => 13040, // listid is required
            'listName' => 'Updated by API', // listName is required
            'descriptiveName' => false, // default = false
            'mobile_prefix' => false // default = false
        );

        $result = $this->emailplatform->UpdateList($params);
        var_dump($result);
    }

    public function CopyList() {
        $params = array(
            'listid' => $listid
        );

        $result = $this->emailplatform->CopyList($params);
        var_dump($result);
    }

    public function DeleteList() {

        /*
         * 
         * @return = bool(true) / string(message)
         * 
         */

        $params = array(
            'listid' => 13233
        );
        $result = $this->emailplatform->DeleteList($params);
        var_dump($result);
    }

    /*
     * 
     * 
     * ContactFields functions 
     * 
     * 
     */

    public function CreateCustomField() {

        /*
         * 
         * @return = int(fieldid) / array(bool, message)
         * 
         */

        $params = array(
            'name' => 'Field Name', // required
            'fieldtype' => 'text', // required
            'fieldsettings' => array(// default = array(), this information varies from which fieldtype is used, more information at https://emailplatform.com/api/php/#CreateCustomField
                'FieldLength' => 500,
                'MaxLength' => 502,
                'MinLength' => 10
            ),
            'listids' => 13040 // can also be an array of more listids
        );

        $result = $this->emailplatform->CreateCustomField($params);
        var_dump($result);
    }

    public function GetCustomFields() {

        /*
         * 
         * @return = array of fields / array(0)
         * 
         */

        $params = array(
            'listids' => 13040 // required, this can be an array of multiple listids
        );

        $result = $this->emailplatform->GetCustomFields($params);
        var_dump($result);
    }

    public function GetSampleDataForOTM() {
        $params = array(
            'fieldid' => $fieldid
        );

        $result = $this->emailplatform->GetSampleDataForOTM($params);
        var_dump($result);
    }

    /*
     * 
     * 
     *  Subscriber functions
     * 
     * 
     */

    public function IsSubscriberOnList() {

        /*
         * 
         * @return = array of subscribers (array will be empty if no subscribers is found).
         * 
         */

        $params = array(
            "listids" => 13040, // can be array with multiple listids
            "emailaddress" => 'kasper@emailplatform.com', // default = false
            "mobile" => false, // default = false
            "mobilePrefix" => false, // default = false
            "subscriberid" => false, // default = false
            "activeonly" => false, // default = false
            "not_bounced" => false, // default = false
            "return_listid" => false                        // default = false
        );

        $result = $this->emailplatform->IsSubscriberOnList($params);
        var_dump($result);
    }

    public function IsUnsubscriber() {

        /*
         * 
         * @return = int(subscriberid) / bool(false)
         * 
         */

        $params = array(
            "listid" => 13040, // EP listid is required
            "emailaddress" => 'kasper@emailplatform.com', // default = false (either subscriberid, email or mobile+mobilePrefix is required) 
            "mobile" => false, // default = false
            "mobilePrefix" => false, // default = false 
            "subscriberid" => false, // default = false
            "service" => false                              // default = false
        );

        $result = $this->emailplatform->IsUnsubscriber($params);
        var_dump($result);
    }

    public function LoadCustomField() {

        /*
         * 
         * @return = array(information) / bool(false)
         * 
         */

        $params = array(
            "fieldid" => 8280, // EP fieldid is required
            "return_options" => false, // default = false
            "makeInstance" => false     // default = false
        );

        $result = $this->emailplatform->LoadCustomField($params);
        var_dump($result);
    }

    public function UnsubscribeSubscriberEmail() {

        /*
         * 
         * @return int(subscriberid) / bool(false)
         * 
         */

        $params = array(
            'listid' => 13040, // EP listid is required
            'emailaddress' => 'kasper2@emailplatform.com', // email or subscriberid is required (default = false)
            'subscriberid' => false, // email or subscriberid is required (default = false)
            'skipcheck' => false, // default = false
            'statid' => false                                   // default = false
        );

        $result = $this->emailplatform->UnsubscribeSubscriberEmail($params);
        var_dump($result);
    }

    public function UnsubscribeSubscriberMobile() {

        /*
         * 
         * @return int(subscriberid) / bool(false)
         * 
         */

        $params = array(
            'listid' => 13040, // EP listid is required
            'mobile' => 26709906, // mobile+mobilePrefix or subscriberid is required
            'mobilePrefix' => 45, // mobile+mobilePrefix or subscriberid is required
            'subscriberid' => false, // mobile+mobilePrefix or subscriberid is required
            'skipcheck' => false, // default = false
            'statid' => false // default = false
        );

        $result = $this->emailplatform->IsUnsubscriber($params);
        var_dump($result);
    }

    public function AddSubscriberToList() {

        /*
         * 
         * @return = int(subscriberid) / string(message)
         * 
         */

        $params = array(
            'listid' => 13040, // EP listid is required
            'emailaddress' => 'kasper@emailplatform.com', // emailaddres or mobile + mobilePrefix is required
            'mobile' => false, // emailaddres or mobile + mobilePrefix is required
            'mobilePrefix' => false, // emailaddres or mobile + mobilePrefix is required
            'contactFields' => array(// contactFields is an array with contact data
                array(
                    'fieldid' => 2, // fieldid for firstname
                    'value' => 'Kasper'
                ),
                array(
                    'fieldid' => 3, // fieldid for last name
                    'value' => 'Bang'
                ),
                array(
                    'fieldid' => 8245, // custom fieldsid for One-to-Many field
                    'value' => array(
                        'Orders' => array(
                            array(
                                'OrderID' => 123,
                                'OrderDate' => '01-01-2010',
                                'OrderText' => 'This is a string'
                            ),
                            array(
                                'OrderID' => 123,
                                'OrderDate' => '01-01-2010',
                                'OrderText' => 'This is a string'
                            ),
                            array(
                                'OrderID' => 123,
                                'OrderDate' => '01-01-2010',
                                'OrderText' => 'This is a string'
                            )
                        )
                    )
                )
            ),
            'add_to_autoresponders' => false, // default = false
            'skip_listcheck' => false, // default = false
            'confirmed' => true // default = true
        );

        $result = $this->emailplatform->AddSubscriberToList($params);
        var_dump($result);
    }

    public function SendNewsletter() {
        $params = array(
            'newsletterid' => 17015, // campaign id from the platform is required
            'subscriberid' => false, // either subscriberid or email is required
            'email' => 'kasper@emailplatform.com', // either subscriberid or email is required
            'fromaddress' => false, // default = false (contact lists informations will be used)
            'fromname' => false, // default = false (contact lists informations will be used)
            'replyaddress' => false // default = false (contact lists informations will be used)
        );

        $result = $this->emailplatform->SendNewsletter($params);
        var_dump($result);
    }

    public function GetSubscribers() {

        $params = array(
            'searchinfo' => array(
                'List' => 13713,
                'Status' => 'active', // unsubscribred, unconfirmed
                'DateSearch' => array(
                    'type' => 'after', // between, before, exactly
                    'StartDate' => '20-06-2018'
                //'EndDate' => '20-07-2018'
                )
            ),
            'countonly' => false, // default = false
            'limit' => 1000,
            'offset' => 0
        );


        // returning all subscribers by using limit and offset
        $stack = array();
        do {
            $result = $this->emailplatform->GetSubscribers($params);

            if (is_array($result)) {
                $stack = array_merge($stack, $result);
            } else {
                die('an error has occurred in GET loop');
            }

            $params['offset'] = $params['offset'] + $params['limit'];
        } while (count($result) == $params['limit']);

        var_dump($stack);
    }

    public function GetSubscriberDetails() {

        $params = array(
            'listid' => 13040, // system listid is required
            'subscriberid' => false, // default is false (either subscribrerid, email or mobile+prefix is requried)
            'emailaddress' => 'kasper@emailplatform.com', // default is false
            'mobile' => false, // default is false
            'mobile_prefix' => 'false' // default is false
        );

        $result = $this->emailplatform->GetSubscriberDetails($params);
        var_dump($result);
    }

    public function GetSubscriberEvents() {

        $params = array(
            'listid' => 12275, // required listid for the subscriber
            'subscriberid' => 22318897, // required subscriberid
            'limit' => 100, // default = 100
            'offset' => 0 // defaut = 0
        );

        // returning all events by using limit and offset
        $stack = array();
        do {
            $result = $this->emailplatform->GetSubscriberEvents($params);

            if (is_array($result)) {
                $stack = array_merge($stack, $result);
            } else {
                die('an error has occurred in GET loop');
            }

            $params['offset'] = $params['offset'] + $params['limit'];
        } while (count($result) == $params['limit']);

        var_dump($stack);
    }

    public function SaveSubscriberCustomField() {

        $params = array(
            'subscriberid' => 23419091, // required system subscriberid
            'fieldid' => 2, // required system fieldid
            'value' => 'Kasper123', // value to update
            'skipEmptyData' => false // skip if value is empty
        );

        $result = $this->emailplatform->SaveSubscriberCustomField($params);
        var_dump($result);
    }

    public function SaveSubscriberCustomFieldByList() {
        $params = array(
            'listid' => 13040,
            'fieldid' => 2,
            'data' => 'Viggo',
            'searchinfo' => 'Kasper123'
        );

        $result = $this->emailplatform->SaveSubscriberCustomFieldByList($params);
        var_dump($result);
    }

    public function LoadSubscriberCustomFields() {

        $params = array(
            'subscriberid' => 23419091,
            'listid' => 13040
        );

        $result = $this->emailplatform->LoadSubscriberCustomFields($params);
        var_dump($result);
    }

    public function DeleteSubscriber() {
        $params = array(
            "listid" => $listid,
            "emailaddress" => $emailaddress,
            "mobile" => $mobile,
            "mobilePrefix" => $mobilePrefix,
            "subscriberid" => $subscriberid
        );

        $result = $this->emailplatform->DeleteSubscriber($params);
        var_dump($result);
    }

    public function UpdateSubscriber() {
        $params = array(
            'listid' => $listid,
            'subscriberid' => $subscriberid,
            'emailaddress' => $emailaddress,
            'mobile' => $mobile,
            'mobilePrefix' => $mobilePrefix,
            'customfields' => $customfields
        );

        $result = $this->emailplatform->UpdateSubscriber($params);
        var_dump($result);
    }

    public function ChangeMobile() {
        $params = array(
            'listid' => $listid,
            'subscriberid' => $subscriberid,
            'mobile' => $mobile,
            'mobilePrefix' => $mobilePrefix
        );

        $result = $this->emailplatform->ChangeMobile($params);
        var_dump($result);
    }

    public function RequestUpdateEmail() {
        $params = array(
            'subscriberid' => $subscriberid,
            'listid' => $listid,
            'oldemail' => $oldemail,
            'newemail' => $newemail,
            'contactFields' => $contactFields
        );
        $result = $this->emailplatform->RequestUpdateEmail($params);
        var_dump($result);
    }

    public function FetchStats() {
        $params = array(
            'statid' => $statid,
            'statstype' => $statstype
        );

        $result = $this->emailplatform->FetchStats($params);
        var_dump($result);
    }

    public function GetBouncesByList() {
        $params = array(
            'listid' => $listid,
            'count_only' => $count_only,
            'bounce_type' => $bounce_type,
            'searchType' => $searchType,
            'searchStartDate' => $searchStartDate,
            'searchEndDate' => $searchEndDate
        );

        $result = $this->emailplatform->GetBouncesByList($params);
        var_dump($result);
    }

    public function GetUnsubscribesByList() {

        $params = array(
            'listid' => $listid,
            'count_only' => $count_only,
            'searchType' => $searchType,
            'searchStartDate' => $searchStartDate,
            'searchEndDate' => $searchEndDate
        );

        $result = $this->emailplatform->GetUnsubscribesByList($params);
        var_dump($result);
    }
    
    public function GetOpens() {
        $params = array(
            'statid' => $statid,
            'count_only' => $count_only,
            'only_unique' => $only_unique
        );
        
        $result = $this->emailplatform->GetOpens($params);
        var_dump($result);
    }

    public function GetRecipients() {
        $params = array(
            'statid' => $statid,
            'count_only' => $count_only
        );

        $result = $this->emailplatform->GetRecipients($params);
        var_dump($result);
    }

    public function CopyNewsletter() {
        $params = array(
            'oldid' => $oldid,
            'name' => $name,
            'subject' => $subject
        );

        $result = $this->emailplatform->CopyNewsletter($params);
        var_dump($result);
    }

    public function GetNewsletters() {
        $params = array(
            'countOnly' => $countOnly,
            'getLastSentDetails' => $getLastSentDetails,
            'content' => $content,
            'aftercreatedate' => $aftercreatedate,
            'newsletterNameLike' => $newsletterNameLike
        );

        $result = $this->emailplatform->GetNewsletters($params);
        var_dump($result);
    }

    public function GetAllListsForEmailAddress() {
        $params = array(
            'emailaddress' => $emailaddress,
            'listids' => $listids,
            'main_listid' => $main_listid,
            'activeonly' => $activeonly,
            'include_deleted' => $include_deleted
        );

        $result = $this->emailplatform->GetAllListsForEmailAddress($params);
        var_dump($result);
    }

    public function ScheduleSendNewsletter() {

        $params = array(
            'campaignid' => $campaignid,
            'hours' => $hours
        );

        $result = $this->emailplatform->ScheduleSendNewsletter($params);
        var_dump($result);
    }

    public function ScheduleSendSMS() {
        $params = array(
            'campaignid' => $campaignid,
            'lists' => $lists,
            'hours' => $hours
        );

        $result = $this->emailplatform->ScheduleSendSMS($params);
        var_dump($result);
    }

    public function GetLatestStats() {
        $params = array(
            'campaignid' => $campaignID,
            'limit' => $limit
        );

        $result = $this->emailplatform->GetLatestStats($params);
        var_dump($result);
    }

    public function GetOneToManySubscriberData() {

        $params = array(
            'subscriberID' => $subscriberID,
            'fieldID' => $fieldID
        );

        $result = $this->emailplatform->GetOneToManySubscriberData($params);
        var_dump($result);
    }

    public function GetListSummary() {

        $params = array(
            'listid' => 13040,
            'limit' => 100,
            'offset' => 0
        );

        $result = $this->emailplatform->GetListSummary($params);
        var_dump($result);
    }

    public function GetSubscribersUpdatedSince() {

        $params = array(
            'date' => '01-01-2018',
            'listid' => 13040,
            'limit' => 1000,
            'offset' => 0
        );

        // returning all events by using limit and offset
        $stack = array();
        do {
            $result = $this->emailplatform->GetSubscribersUpdatedSince($params);

            if (is_array($result)) {
                $stack = array_merge($stack, $result);
            } else {
                die('an error has occurred in GET loop');
            }

            $params['offset'] = $params['offset'] + $params['limit'];
        } while (count($result) == $params['limit']);

        var_dump($stack);
    }

}
