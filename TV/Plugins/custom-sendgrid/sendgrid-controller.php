<?php

/**
 * Plugin Name: Custom SendGrid
 * Plugin URI: http://www.mywebsite.com/my-first-plugin
 * Description: Custom Sendgrid is a Custom Plugin. It is created to maintain all SendGrid API V3 call in one place. *Keep this plugin activated at all times.*
 * Version: 1.0
 * Author: Shilpi Dave
 * Author URI: http://www.mywebsite.com
 */
class sendgridController {

    //const SG_API_KEY = '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';
	const SG_API_KEY = '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';	
	//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
    //const SG_SUBSCRIPTIONS_LISTID = '36097848-0263-4fda-9648-506b0f2e3408';   Old Contact List
	const SG_SUBSCRIPTIONS_LISTID = 'd8a16bc1-92ec-46a8-bc61-55f6dd02b76a';
    const SG_REGISTRATION_LISTID = '4c879862-f9e5-453b-9994-bd0f7e7b2e0d';
    
    const SG_DYNAMIC_EMAIL_TEMPLATES = array(
        'new_subscriber' => 'd-e7bf25c5b1eb48e09e612ff743e26091',
        'newsletter_daily' => 'd-4eaa06e31f5b46969cb1f8d19e8d4b6b',
        'newsletter_weekly' => 'd-64976a0dc67d43e38b020270f469d316',
        'newsletter_monthly' => 'd-c07df2b73b80457985c9bb10ae77b711',
        'new_subscriber_email_confirmation' => 'd-9aedee2acb09468eaf49209efb535269',
    );

    const SG_UNSUBSCRIBE_GROUPS = array(
        'daily' => '30491',
        'weekly' => '15685',
        'monthly' => '15686',
        'TV_Email_Marketing' => '16564',
    );
    const SG_UNSUBSCRIBE_GROUP_DESC = array(
        '30491' => 'From AI and VR to Blockchain and Cloud, get your daily dose of tech news, trends and much more. Subscribe now.',
        '15685' => 'Subscribe to our weekly newsletter for latest updates on "everything tech", delivered right to your inbox.',
        '15686' => 'The monthly brief covers a variety of popular tech stories and latest happenings, including blogs and editors\' picks.',
    );
    const SG_UNSUBSCRIBE_GROUP_FREQUENCY = array(
        '30491' => '',
        '15685' => 'On every Thursday',
        '15686' => '',
    );

    function send_dynamic_template() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/lists/2881f56b-2ac4-4052-99db-06b657444d01?contact_sample=true",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . self::SG_API_KEY
            ),
        ));

        $err = curl_error($curl);

        $response = curl_exec($curl);
        $list_contact = json_decode($response);

        $contact_list = array();
        foreach ($list_contact->contact_sample as $single_contact) {
            $contact_list[] = array(
                'name' => (!empty($single_contact->name)) ? $single_contact->name : $single_contact->email,
                'email' => $single_contact->email,
            );
        }

        curl_close($curl);

        $curl = curl_init();

        $json_fields = array(
            "personalizations" => array(array(
                    "to" => $contact_list,
                    "dynamic_template_data" => array(
                        'dynamic_variable' => "Hello World - Dynamic Variable"
                    ),
                    "subject" => "Hello, World!"
                )),
            "from" => array(
                "email" => "info@techversions.com",
                "name" => "TechVersions"
            ),
            "reply_to" => array(
                "email" => "info@techversions.com",
                "name" => "TechVersions"
            ),
            "asm" => array(
                "group_id" => 20038,
                "groups_to_display" => array(20038),
            ),
            "template_id" => "d-c5687e792a2544bca1954853e32d7530"
        );


        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($json_fields),
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . self::SG_API_KEY,
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    static function add_to_list($data = array()) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/contacts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . self::SG_API_KEY,
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $msg = "cURL Error #:" . $err;
        } else {
            $msg = $response;
        }
        return $msg;
    }

    static function fetch_groups() {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/asm/groups",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . self::SG_API_KEY
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $msg = "cURL Error #:" . $err;
        } else {
            $msg = $response;
        }

        return $msg;
    }

    static function update_suppression_group($data) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/asm/groups/%7Bgroup_id%7D/suppressions",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "$data",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . self::SG_API_KEY,
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    static function remove_receipient($data = array()) {
        $group_id = $_POST['group_id'];
        $curr_user = wp_get_current_user();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/asm/groups/$group_id/suppressions/" . $curr_user->data->user_email,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_POSTFIELDS => "null",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . self::SG_API_KEY,
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        $msg = array();
        if ($err) {
            $msg = array(
                'status' => 'error',
                'message' => "cURL Error #:" . $err,
            );
        } else {
            $msg = array(
                'status' => 'success',
            );
        }

        return json_encode($msg);
        exit(0);
    }


    static function get_supression_group($email = '') {

        if(empty($email)) {
            $user_ID = get_current_user_id(); 
            $curr_user = get_user_by( 'id', $user_ID );
            //$curr_user = wp_get_current_user();
            $email = $curr_user->user_email;
        }
        $user_email = $email;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/asm/suppressions/$user_email",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . self::SG_API_KEY,
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $msg = array();
        if ($err) {
            $msg = array(
                'status' => 'error',
                'message' => "cURL Error #:" . $err,
            );
        } else {
            $msg = array(
                'status' => 'success',
                'response' => $response,
            );
        }
        return $msg;
    }

    static function new_post_email_update($template_arr = array()) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/lists/c3bf0f95-890f-4370-99dd-6b8ff4d67d6d?contact_sample=true",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . self::SG_API_KEY,
            ),
        ));

        $err = curl_error($curl);

        $response = curl_exec($curl);
        $list_contact = json_decode($response);

        $contact_list = array();
        foreach ($list_contact->contact_sample as $single_contact) {
            $contact_list[] = array(
                'name' => (!empty($single_contact->name)) ? $single_contact->name : $single_contact->email,
                'email' => $single_contact->email,
            );
        }

        curl_close($curl);

        $curl = curl_init();

        $json_fields = array(
            "personalizations" => array(array(
                    "to" => $contact_list,
                    "dynamic_template_data" => array(
                        'post_details' => $template_arr['content']
                    ),
                    "subject" => "Hello, World!"
                )),
            "from" => array(
                "email" => "info@techversions.com",
                "name" => "TechVersions"
            ),
            "reply_to" => array(
                "email" => "info@techversions.com",
                "name" => "TechVersions"
            ),
            "asm" => array(
                "group_id" => 20039,
                "groups_to_display" => array(20039),
            ),
            "template_id" => "d-64dff65bb60a4273876efbb88a06ebb0"
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($json_fields),
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . self::SG_API_KEY,
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    static function new_post_email_preference($template_id, $group_id, $template_arr = array()) {
        $contact_list[] = array(
            'name' => 'svyas@trueinfluence.com',
            'email' => 'svyas@trueinfluence.com',
        );
        $contact_list[] = array(
            'name' => 'raamdhorai@trueinfluence.com',
            'email' => 'raamdhorai@trueinfluence.com',
        );
        $contact_list[] = array(
            'name' => 'gvivek@trueinfluence.com',
            'email' => 'gvivek@trueinfluence.com',
        );
        $contact_list[] = array(
            'name' => 'ramkiran@trueinfluence.com',
            'email' => 'ramkiran@trueinfluence.com',
        );
        $contact_list[] = array(
            'name' => 'smagesh@trueinfluence.com',
            'email' => 'smagesh@trueinfluence.com',
        );

        $curl = curl_init();

        $json_fields = array(
            "personalizations" => array(array(
                    "to" => $contact_list,
                    "dynamic_template_data" => $template_arr,
                    "receipt" => true,
                    "name" => "Sample Name",
                    "address01" => "1234 Fake St.",
                    "address02" => "Apt. 123",
                    "city" => "Place",
                    "state" => "CO",
                    "zip" => "80202"
                )),
            "from" => array(
                "email" => "info@techversions.com",
                "name" => "TechVersions"
            ),
            "reply_to" => array(
                "email" => "info@techversions.com",
                "name" => "TechVersions"
            ),
//            "asm" => array(
//                "group_id" => $group_id,
//                "groups_to_display" => array($group_id),
//            ),
            "template_id" => $template_id,
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($json_fields),
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . self::SG_API_KEY,
                "content-type: application/json"
        )));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    static function get_group_data($group_id) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/asm/groups/$group_id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . self::SG_API_KEY,
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $msg = '';
        if ($err) {
            $msg = "cURL Error #:" . $err;
        } else {
            $msg = $response;
        }
        return $msg;
    }

    static function send_dynamic_email($template_id, $group_id, $template_arr, $contact_list_arr, $single_user = TRUE) {
        if (!$single_user) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/lists/c3bf0f95-890f-4370-99dd-6b8ff4d67d6d?contact_sample=true",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_POSTFIELDS => "{}",
                CURLOPT_HTTPHEADER => array(
                    "authorization: Bearer " . self::SG_API_KEY,
                ),
            ));

            $err = curl_error($curl);

            $response = curl_exec($curl);
            $list_contact = json_decode($response);

            $contact_list = array();
            foreach ($list_contact->contact_sample as $single_contact) {
                $contact_list[] = array(
                    'name' => (!empty($single_contact->name)) ? $single_contact->name : $single_contact->email,
                    'email' => $single_contact->email,
                );
            }
            //        START -- For Testing
            //        $contact_list[] = array(
            //            'name' => 'svyas@trueinfluence.com',
            //            'email' => 'svyas@trueinfluence.com',
            //        );
            //        END -- For Testing

            curl_close($curl);
        } else {
            $contact_list = $contact_list_arr;
        }

        $curl = curl_init();
        $json_fields = array(
            "personalizations" => $contact_list,
            "from" => array(
                "email" => "info@techversions.com",
                "name" => "TechVersions"
            ),
            "reply_to" => array(
                "email" => "info@techversions.com",
                "name" => "TechVersions"
            ),
//            "asm" => array(
//                "group_id" => $group_id,
//                "groups_to_display" => array($group_id),
//            ),
            "template_id" => $template_id,
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($json_fields),
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . self::SG_API_KEY,
                "content-type: application/json"
        )));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    static function send_newsletter($template_id, $group_id = '', $template_arr = array(), $email_type = 'weekly', $send_now='false') {
        global $wpdb;
        $curl = curl_init();
		
		

        curl_setopt_array($curl, array(
            //CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/contacts",
			CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/lists/d8a16bc1-92ec-46a8-bc61-55f6dd02b76a?contact_sample=true",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . self::SG_API_KEY,
            ),
        ));

        $err = curl_error($curl);

        $response = curl_exec($curl);
        $list_contact = json_decode($response);
        $contact_list = array();
        /*echo '<pre>';
        print_r($list_contact);
        echo '</pre>';*/
       
        //foreach ($list_contact->result as $single_contact) {
        foreach ($list_contact->contact_sample as $single_contact) {

            $login_ip = $wpdb->get_row("SELECT * FROM subscriptions WHERE email_id LIKE '{$single_contact->email}' ORDER BY id DESC;", ARRAY_A);
            $user_ip_address = (!empty($login_ip) && isset($login_ip['ip_address'])) ? $login_ip['ip_address'] : '';

            if (empty($user_ip_address)) {
                $login_ip = $wpdb->get_row("SELECT u.id, um.meta_value as user_ip_address FROM wp_users u JOIN wp_usermeta um ON u.id = um.user_id AND um.meta_key = 'signup_ip' WHERE u.user_email LIKE '{$single_contact->email}' ORDER BY u.id DESC;", ARRAY_A);
                $user_ip_address = (!empty($login_ip) && isset($login_ip['user_ip_address'])) ? $login_ip['user_ip_address'] : '';
            }

            if (empty($user_ip_address)) {
                $login_ip = $wpdb->get_row("SELECT * FOM wp_fa_user_logins ul JOIN wp_users u ON u.id = ul.user_id WHERE u.user_email LIKE '{$single_contact->email}' ORDER BY ul.id DESC;", ARRAY_A);
                $user_ip_address = (!empty($login_ip) && isset($login_ip['ip_address'])) ? $login_ip['ip_address'] : (isset($login_ip['user_ip_address']) ? $login_ip['user_ip_address'] : '');
            }

            if (!empty($user_ip_address)) {
//                $url = "https://tools.keycdn.com/geo.json?host={$user_ip_address}";
                $url = "http://ip-api.com/json/{$user_ip_address}?key=D85DpIqP6D4LWSvbF4CGrnY2uOYe1Xb46Dqh7yX08RRuuq1am7";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_SSLVERSION, 6);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
                curl_setopt($ch, CURLOPT_TIMEOUT, 150);
                $err = curl_error($ch);
                $response = curl_exec($ch);
                curl_close($ch);

                $json = json_decode($response, true);
//                $time_zone = $json['data']['geo']['timezone'];
                $time_zone = $json['timezone'];
            } else {
                $time_zone = 'America/New_York';
            }

            /*
             * Get Timestamp of 10 am for the current date, change it to 8pm after the testing
             */

            if ($email_type == 'daily') {
                $date = new DateTime(date('Y-m-d 12:00'), new DateTimeZone($time_zone));
            } else if ($email_type == 'weekly') {
                $date = new DateTime(date('Y-m-d 10:00'), new DateTimeZone($time_zone));
            } else {
                $date = new DateTime(date('Y-m-d 09:00'), new DateTimeZone($time_zone));
            }
            $timestamp = $date->format('U');
            /*
             * Current Time based on timezone
             * $new_date = new DateTime("now", new DateTimeZone($time_zone) );
             * $new_date->format('Y-m-d H:i')
             * Test User - 'raamdhorai@trueinfluence.com','gvivek@trueinfluence.com','ramkiran@trueinfluence.com','smagesh@trueinfluence.com','abiswal@trueinfluence.com','kmshrivani@trueinfluence.com','santhoshkumar@trueinfluence.com'
             */
            
//            if (in_array($single_contact->email, array('svyas@trueinfluence.com'))) {

            $check_email_delivered = $wpdb->get_row("SELECT * FROM email_delivered WHERE email_id LIKE '{$single_contact->email}' and email_key LIKE 'newsletter_".$email_type."_".date('Y_m_d')."' ORDER BY id DESC;", ARRAY_A);
            if(empty($check_email_delivered)) {
                if($send_now === 'false') {
                    $contact_list[] = array(
                        'to' => array(array(
                                'name' => (!empty($single_contact->name)) ? $single_contact->name : $single_contact->email,
                                'email' => $single_contact->email,
                            )),
                        'dynamic_template_data' => $template_arr,
                        'send_at' => (int) $timestamp
                    );
                } else {
                    $contact_list[] = array(
                        'to' => array(array(
                                'name' => (!empty($single_contact->name)) ? $single_contact->name : $single_contact->email,
                                'email' => $single_contact->email,
                            )),
                        'dynamic_template_data' => $template_arr,
                    );
                }

                //Save the contact details
                $wpdb->insert('email_delivered', array(
                    'email_id'      => $single_contact->email,
                    'email_type'    => "newsletter_$email_type",
                    'email_key'     => "newsletter_".$email_type."_".date('Y_m_d'),
                    'date'          => date('Y-m-d H:i:s')
                ));
            }
        
//            }
        }

        curl_close($curl);

        $curl = curl_init();
        $json_fields = array(
            "personalizations" => $contact_list,
            "from" => array(
                "email" => "noreply@newsletter.techversions.com",
                "name" => "TechVersions"
            ),
            "reply_to" => array(
                "email" => "noreply@newsletter.techversions.com",
                "name" => "TechVersions"
            ),
            "asm" => array(
                "group_id" => (int) $group_id,
                "groups_to_display" => array((int) $group_id),
            ),
            "template_id" => $template_id,
        );

        echo '<pre>';
        print_r($json_fields);
        echo '</pre>';

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($json_fields),
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . self::SG_API_KEY,
                "content-type: application/json"
        )));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo 'Firing email at :' . date('Y-m-d H:i:s');
            echo $response;
            $headers[] = 'From: Newsletter <noreply@newsletter.techversions.com>';
            wp_mail('svyas@trueinfluence.com', $email_type . ' - WP Crontrol', $email_type . ' - WP Crontrol just ran at ' . date('Y-m-d H:i:s') . '!',$headers);
        }
    }

    static function send_newsletter_test($template_id, $group_id = '', $template_arr = array(), $email_type = 'weekly', $send_now='false') {
        global $wpdb;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            //CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/contacts",
			CURLOPT_URL => "https://api.sendgrid.com/v3/marketing/lists/d8a16bc1-92ec-46a8-bc61-55f6dd02b76a?contact_sample=true",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . self::SG_API_KEY,
            ),
        ));

        $err = curl_error($curl);

        $response = curl_exec($curl);
        $list_contact = json_decode($response);
        $contact_list = array();
        /*echo '<pre>';
        print_r($list_contact);
        echo '</pre>';*/
       
        //foreach ($list_contact->result as $single_contact) {
        foreach ($list_contact->contact_sample as $single_contact) {

            $login_ip = $wpdb->get_row("SELECT * FROM subscriptions WHERE email_id LIKE '{$single_contact->email}' ORDER BY id DESC;", ARRAY_A);
            $user_ip_address = (!empty($login_ip) && isset($login_ip['ip_address'])) ? $login_ip['ip_address'] : '';

            if (empty($user_ip_address)) {
                $login_ip = $wpdb->get_row("SELECT u.id, um.meta_value as user_ip_address FROM wp_users u JOIN wp_usermeta um ON u.id = um.user_id AND um.meta_key = 'signup_ip' WHERE u.user_email LIKE '{$single_contact->email}' ORDER BY u.id DESC;", ARRAY_A);
                $user_ip_address = (!empty($login_ip) && isset($login_ip['user_ip_address'])) ? $login_ip['user_ip_address'] : '';
            }

            if (empty($user_ip_address)) {
                $login_ip = $wpdb->get_row("SELECT * FOM wp_fa_user_logins ul JOIN wp_users u ON u.id = ul.user_id WHERE u.user_email LIKE '{$single_contact->email}' ORDER BY ul.id DESC;", ARRAY_A);
                $user_ip_address = (!empty($login_ip) && isset($login_ip['ip_address'])) ? $login_ip['ip_address'] : (isset($login_ip['user_ip_address']) ? $login_ip['user_ip_address'] : '');
            }

            if (!empty($user_ip_address)) {
//                $url = "https://tools.keycdn.com/geo.json?host={$user_ip_address}";
                $url = "http://ip-api.com/json/{$user_ip_address}?key=D85DpIqP6D4LWSvbF4CGrnY2uOYe1Xb46Dqh7yX08RRuuq1am7";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_SSLVERSION, 6);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
                curl_setopt($ch, CURLOPT_TIMEOUT, 150);
                $err = curl_error($ch);
                $response = curl_exec($ch);
                curl_close($ch);

                $json = json_decode($response, true);
//                $time_zone = $json['data']['geo']['timezone'];
                $time_zone = $json['timezone'];
            } else {
                $time_zone = 'America/New_York';
            }

            /*
             * Get Timestamp of 10 am for the current date, change it to 8pm after the testing
             */

            if ($email_type == 'daily') {
                $date = new DateTime(date('Y-m-d 12:00'), new DateTimeZone($time_zone));
            } else if ($email_type == 'weekly') {
                $date = new DateTime(date('Y-m-d 10:00'), new DateTimeZone($time_zone));
            } else {
                $date = new DateTime(date('Y-m-d 09:00'), new DateTimeZone($time_zone));
            }
            $timestamp = $date->format('U');
            /*
             * Current Time based on timezone
             * $new_date = new DateTime("now", new DateTimeZone($time_zone) );
             * $new_date->format('Y-m-d H:i')
             * Test User - 'raamdhorai@trueinfluence.com','gvivek@trueinfluence.com','ramkiran@trueinfluence.com','smagesh@trueinfluence.com','abiswal@trueinfluence.com','kmshrivani@trueinfluence.com','santhoshkumar@trueinfluence.com'
             */
            
//            if (in_array($single_contact->email, array('svyas@trueinfluence.com'))) {

            $check_email_delivered = $wpdb->get_row("SELECT * FROM email_delivered WHERE email_id LIKE '{$single_contact->email}' and email_key LIKE 'newsletter_".$email_type."_".date('Y_m_d')."' ORDER BY id DESC;", ARRAY_A);
            if(empty($check_email_delivered)) {
                if($send_now === 'false') {
                    $contact_list[] = array(
                        'to' => array(array(
                                'name' => (!empty($single_contact->name)) ? $single_contact->name : $single_contact->email,
                                'email' => $single_contact->email,
                            )),
                        'dynamic_template_data' => $template_arr,
                        'send_at' => (int) $timestamp
                    );
                } else {
                    $contact_list[] = array(
                        'to' => array(array(
                                'name' => (!empty($single_contact->name)) ? $single_contact->name : $single_contact->email,
                                'email' => $single_contact->email,
                            )),
                        'dynamic_template_data' => $template_arr,
                    );
                }

                //Save the contact details
                $wpdb->insert('email_delivered', array(
                    'email_id'      => $single_contact->email,
                    'email_type'    => "newsletter_$email_type",
                    'email_key'     => "newsletter_".$email_type."_".date('Y_m_d'),
                    'date'          => date('Y-m-d H:i:s')
                ));
            }
        
//            }
        }

        curl_close($curl);

        $curl = curl_init();
        $json_fields = array(
            "personalizations" => $contact_list,
            "from" => array(
                "email" => "noreply@newsletter.techversions.com",
                "name" => "TechVersions"
            ),
            "reply_to" => array(
                "email" => "noreply@newsletter.techversions.com",
                "name" => "TechVersions"
            ),
            "asm" => array(
                "group_id" => (int) $group_id,
                "groups_to_display" => array((int) $group_id),
            ),
            "template_id" => $template_id,
        );

        echo '<pre>';
        print_r($json_fields);
        echo '</pre>';

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($json_fields),
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . self::SG_API_KEY,
                "content-type: application/json"
        )));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo 'Firing email at :' . date('Y-m-d H:i:s');
            echo $response;
            $headers[] = 'From: Newsletter <noreply@newsletter.techversions.com>';
            wp_mail('svyas@trueinfluence.com', $email_type . ' - WP Crontrol', $email_type . ' - WP Crontrol just ran at ' . date('Y-m-d H:i:s') . '!',$headers);
        }
    }

    static function sg_unsubscribe_from_all($data = array()) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/asm/suppressions/global",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode(array(
                'recipient_emails' => array(
                    $data['email_id']
                )
            )),
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . self::SG_API_KEY,
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $msg = array();
        if ($err) {
            $msg = array(
                'status' => 'error',
                'message' => "cURL Error #:" . $err,
            );
        } else {
            $msg = array(
                'status' => 'success',
            );
        }

        return $msg;
    }

    static function add_user_suppression_group($data = array()) {
        $group_id = $data['group_id'];
        $recipient_emails = array(
            "recipient_emails" => array(
                $data['email_id'],
        ));

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/asm/groups/$group_id/suppressions",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($recipient_emails),
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . sendgridController::SG_API_KEY,
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        $msg = array();
        if ($err) {
            $msg = array(
                'status' => 'error',
                'message' => "cURL Error #:" . $err,
            );
        } else {
            $msg = array(
                'status' => 'success',
            );
        }

        return json_encode($msg);
    }

    static function remove_user_suppression_group($data = array()) {
        $group_id = $data['group_id'];
        $email = $data['email_id'];
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/asm/groups/$group_id/suppressions/" . $email,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_POSTFIELDS => "null",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . sendgridController::SG_API_KEY,
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        $msg = array();
        if ($err) {
            $msg = array(
                'status' => 'error',
                'message' => "cURL Error #:" . $err,
            );
        } else {
            $msg = array(
                'status' => 'success',
            );
        }

        return json_encode($msg);
    }

}