<?php


function get_row($table_name='', $id_array='')
  {
    $CI  =&get_instance();    
    if(!empty($id_array)):    
      foreach ($id_array as $key => $value){
        $CI->db->where($key, $value);
      }
    endif;

    $query=$CI->db->get($table_name);
    if($query->num_rows()>0)
      return $query->row();
    else
      return FALSE;
  }


  function get_result($table_name='', $id_array='')
  {
    $CI  =&get_instance();    
    if(!empty($id_array)):    
      foreach ($id_array as $key => $value){
        $CI->db->where($key, $value);
      }
    endif;

    $query=$CI->db->get($table_name);
    if($query->num_rows()>0)
      return $query->result();
    else
      return FALSE;
  }

  

function get_manager_id(){
    $CI =& get_instance();
    $user_info = $CI->session->userdata('managerInfo');    
    return $user_info['id'];
}

function manager_login_in(){
    $CI =& get_instance();
    $user_info = $CI->session->userdata('managerInfo');    
    if($user_info['logged_in']===TRUE ){
      return TRUE;    
    }else{
      return FALSE;       
    }
}

function get_manager_name() { 
    $CI =& get_instance();
    $user_info = $CI->session->userdata('managerInfo');    
    return $user_info['fname'].' '.$user_info['lname'];
}

/**

* check Admin authentication

*/

if ( ! function_exists('admin_login_in')) {  

  function admin_login_in() {

    $CI =& get_instance();

    $user_info=$CI->session->userdata('AdminInfo');    

    if($user_info['logged_in']===TRUE && $user_info['role'] == 1)

      return TRUE;    

    else

      return FALSE;       

  }

}
if ( ! function_exists('trainer_login_in')) {  

  function trainer_login_in() {

    $CI =& get_instance();

    $user_info=$CI->session->userdata('trainerInfo');    

    if($user_info['logged_in']===TRUE )

      return TRUE;    

    else

      return FALSE;       

  }

}

if ( ! function_exists('get_trainer_id')) {  

  function get_trainer_id() {

    $CI =& get_instance();

    $user_info=$CI->session->userdata('trainerInfo');    

    return $user_info['id'];

  }

}

if ( ! function_exists('get_trainee_id')) {  

  function get_trainee_id() {

    $CI =& get_instance();

    $user_info=$CI->session->userdata('traineeInfo');    

    return $user_info['id'];

  }

}
if ( ! function_exists('trainee_login_in')) {  

  function trainee_login_in() {

    $CI =& get_instance();

    $user_info=$CI->session->userdata('traineeInfo');    

    if($user_info['logged_in']===TRUE )

      return TRUE;    

    else

      return FALSE;       

  }

}



/**

* alert

*/

if ( ! function_exists('alert')) {  

  function alert() {

    $CI =& get_instance();

    if ($CI->session->flashdata('success_msg')){

      echo success_alert($CI->session->flashdata('success_msg'));

    }

    if ($CI->session->flashdata('error_msg')){

      echo error_alert($CI->session->flashdata('error_msg')); 

    }

    if ($CI->session->flashdata('info_msg')){

      echo error_alert($CI->session->flashdata('info_msg')); 

    }

    $js = "";

    $js .= "<script>";

    $js .= "$(document).ready(function(){";

    $js .= "setTimeout(function(){";

    $js .= "$('.alert').fadeOut('slow');";

    $js .= "}, 2000);";

    $js .= "});";

    $js .= "</script>";



    echo $js;

  }

}



/**

* Success alert

*/

if ( ! function_exists('success_alert')) {

  function success_alert($msg = '') { ?>

    <div class="alert alert-success ci_alert alert-dismissable">

        <button data-dismiss="alert" class="close" type="button">×</button>

        <strong>Success!</strong> <?php echo $msg ?>

    </div>

    <?php 

  }

}



/**

* Error alert

*/

if ( ! function_exists('error_alert')) {  

  function error_alert($msg = '') {?>

    <div class="alert alert-danger ci_alert alert-dismissable">

      <button data-dismiss="alert" class="close" type="button">×</button>

          <strong>Error!</strong> <?php echo $msg ?>

      </div>

    <?php 

    }

}



/**

* info alert

*/

if ( ! function_exists('info_alert')) { 

  function info_alert($msg = '') {?>

    <div class="alert alert-info ci_alert alert-dismissable">

      <button data-dismiss="alert" class="close" type="button">×</button>

      <strong>Error!</strong> <?php echo $msg ?>

    </div>

  <?php 

  }

}



/**

* get usename

*/

if ( ! function_exists('login_username')) { 

  function login_username() {

    $CI =& get_instance();    

    $user_info = $CI->session->userdata('UserInfo');    

    return $user_info['display_name'];  

  }

}



/**

* clear cache

*/

if ( ! function_exists('clear_cache')) {

  function clear_cache(){

    $CI =& get_instance();

    $CI->output->set_header('Expires: Wed, 11 Jan 1984 05:00:00 GMT' );

    $CI->output->set_header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . 'GMT');

    $CI->output->set_header("Cache-Control: no-cache, no-store, must-revalidate");

    $CI->output->set_header("Pragma: no-cache");      

  }

}



/**

* convert id to encrypt code

*/

if ( ! function_exists('id_encrypt')) { 

  function id_encrypt($id = NULL) {

    $CI =& get_instance();

    return $CI->encrypt->encode($id);

  }

}



/**

* convert decrypt code to id

*/

if ( ! function_exists('id_decrypt')) { 

  function id_decrypt($code = NULL) {

    $CI =& get_instance();

    return $CI->encrypt->decode($code);

  }

}





/* get_theme pagination */

if ( ! function_exists('get_theme_pagination')) {

  function get_theme_pagination(){

    $data = array();

    $data['cur_tag_open'] = '<li class="disabled"><a>';

    $data['cur_tag_close'] = '<</li>';

    $data['full_tag_open'] = '<div style="padding-left:10px"><ul class="pagination">';

    $data['full_tag_close'] = '</ul></div>';

    $data['first_tag_open'] = '<li>';

    $data['first_tag_close'] = '</li>';

    $data['num_tag_open'] = '<li>';

    $data['num_tag_close'] = '</li>';

    $data['last_tag_open'] = '<li>';

    $data['last_tag_close'] = '</li>';

    $data['next_tag_open'] = '<li>';

    $data['next_tag_close'] = '</li>';

    $data['prev_tag_open'] = '<li>';

    $data['prev_tag_close'] = '</li>';

    $data['next_link'] = '&raquo;';

    $data['prev_link'] = '&laquo;';

    $data['cur_tag_open'] = '<li class="active"><a>';

    $data['cur_tag_close'] = '</a></li>';

    return $data;

  }

}







/* theme pagination */

if ( ! function_exists('theme_pagination')) {

  function theme_pagination(){

    $data = array();

    $data['cur_tag_open'] = '<li class="disabled"><a>';

    $data['cur_tag_close'] = '<</li>';

    $data['full_tag_open'] = '<div class="blog_nav"><ul>';

    $data['full_tag_close'] = '</ul></div>';

    $data['first_tag_open'] = '<li>';

    $data['first_tag_close'] = '</li>';

    $data['num_tag_open'] = '<li>';

    $data['num_tag_close'] = '</li>';

    $data['last_tag_open'] = '<li>';

    $data['last_tag_close'] = '</li>';

    $data['next_tag_open'] = '<li>';

    $data['next_tag_close'] = '</li>';

    $data['prev_tag_open'] = '<li>';

    $data['prev_tag_close'] = '</li>';

    $data['next_link'] = '&raquo;';

    $data['prev_link'] = '&laquo;';

    $data['cur_tag_open'] = '<li class="active"><a>';

    $data['cur_tag_close'] = '</a></li>';

    return $data;

  }

}



if (!function_exists('remove_special_character')){

  function remove_special_character($string=''){

    return str_replace(array('!','@','#','$','%','^','&','*','(',')','+','{','}','[',']',':','\'', '"', ',' , ';', '<', '>','|','\\','?','/' ), '', $string);           

  }

}



/**

* Create Slug

*/

if ( ! function_exists('create_slug')) {  

  function create_slug($tablename = '', $title = '') {

    $title = substr($title, 0, 15);

    $CI =& get_instance();

    $slug = str_replace(array('!','@','#','$','%','^','&','*','(',')','+','{','}','[',']',':','\'', '"', ',' , ';', '<', '>','|','\\','?','/' ), ' ', $title);     

    $slug = str_replace(' ', '-', $slug);

    $slug = strtolower($slug);

    return get_slug($tablename, $slug);

  }

}



if ( ! function_exists('get_slug')) {  

  function get_slug($tablename = '', $slug = '', $append = '') {

    $CI =& get_instance();

    $CI->db->select('slug');

    

    if($append == '')

      $CI->db->where('slug', $slug);

    else

      $CI->db->where('slug', $slug.'-'.$append);

    

    $query = $CI->db->get($tablename);

    if($query->num_rows() > 0){

      if($append == '')

        $append = 1;

      else

        $append = $append + 1;

      return get_slug($tablename, $slug, $append);

    }

    else{

      if($append == '')

        return $slug;

      else

        return $slug.'-'.$append;

    }

  }

}



/**

* Create Slug

*/

if ( ! function_exists('create_slug_for_update')) {  

  function create_slug_for_update($tablename = '', $title = '', $id = '') {

    $title = substr($title, 0, 15);

    $CI =& get_instance();

    $slug = str_replace(array('!','@','#','$','%','^','&','*','(',')','+','{','}','[',']',':','\'', '"', ',' , ';', '<', '>','|','\\','?','/' ), ' ', $title);     

    $slug = str_replace(' ', '-', $slug);

    $slug = strtolower($slug);

    return get_slug_for_update($tablename, $slug, $id);

  }

}



if ( ! function_exists('get_slug_for_update')) {  

  function get_slug_for_update($tablename = '', $slug = '', $id = '' , $append = '') {

    $CI =& get_instance();

    $CI->db->select('slug');

    

    $CI->db->where('id !=', $id);

    if($append == ''){

      $CI->db->where('slug', $slug);

    }

    else{

      $CI->db->where('slug', $slug.'-'.$append);

    }

    

    $query = $CI->db->get($tablename);

    if($query->num_rows() > 0){

      if($append == '')

        $append = 1;

      else

        $append = $append + 1;

      return get_slug_for_update($tablename, $slug, $id , $append);

    }

    else{

      if($append == '')

        return $slug;

      else

        return $slug.'-'.$append;

    }

  }

}



function create_thumb($file = '', $path = './assets/uploads/' ){

  $thumbpath = $path.'thumbs/';

  if (!is_writable($thumbpath)) {

        if (!chmod($thumbpath, 0777)) {

            return FALSE;

        }

    }

    $CI =& get_instance();

    $CI->load->library('image_lib');

  $config['image_library'] = 'gd2';

  $config['source_image'] = $path.$file;

  $config['new_image'] = $thumbpath.$file;

  $config['quality'] = '100%';

  $config['maintain_ratio'] = TRUE;

  $config['width'] = 200;

  $config['height'] = 200;        

  $CI->image_lib->initialize($config);

  if ( ! $CI->image_lib->resize()){

    return FALSE;

  }else{

    return TRUE;

  }

}



function delete_image($file = '', $path = './assets/uploads/' ){

  $thumb = $path.'thumbs/'.$file;

  $image = $path.$file;

  @unlink($thumb);

  @unlink($image);

  return TRUE;

}





if ( ! function_exists('get_country_array'))

  { 

    function get_country_array()

    {

      

      return array(

              "US"=>"United States",

              "AF"=>"Afghanistan",

              "AX"=>"Aland Islands",

              "AL"=>"Albania",

              "DZ"=>"Algeria",

              "AS"=>"American Samoa",

              "AD"=>"Andorra",

              "AO"=>"Angola",

              "AI"=>"Anguilla",

              "AQ"=>"Antarctica",

              "AG"=>"Antigua and Barbuda",

              "AR"=>"Argentina",

              "AM"=>"Armenia",

              "AW"=>"Aruba",

              "AU"=>"Australia",

              "AT"=>"Austria",

              "AZ"=>"Azerbaijan",

              "BS"=>"Bahamas",

              "BH"=>"Bahrain",

              "BD"=>"Bangladesh",

              "BB"=>"Barbados",

              "BY"=>"Belarus",

              "BE"=>"Belgium",

              "BZ"=>"Belize",

              "BJ"=>"Benin",

              "BM"=>"Bermuda",

              "BT"=>"Bhutan",

              "BO"=>"Bolivia, Plurinational State of",

              "BQ"=>"Bonaire, Sint Eustatius and Saba",

              "BA"=>"Bosnia and Herzegovina",

              "BW"=>"Botswana",

              "BV"=>"Bouvet Island",

              "BR"=>"Brazil",

              "IO"=>"British Indian Ocean Territory",

              "BN"=>"Brunei Darussalam",

              "BG"=>"Bulgaria",

              "BF"=>"Burkina Faso",

              "BI"=>"Burundi",

              "KH"=>"Cambodia",

              "CM"=>"Cameroon",

              "CA"=>"Canada",

              "CV"=>"Cape Verde",

              "KY"=>"Cayman Islands",

              "CF"=>"Central African Republic",

              "TD"=>"Chad",

              "CL"=>"Chile",

              "CN"=>"China",

              "CX"=>"Christmas Island",

              "CC"=>"Cocos (Keeling) Islands",

              "CO"=>"Colombia",

              "KM"=>"Comoros",

              "CG"=>"Congo",

              "CD"=>"Congo, The Democratic Republic of the",

              "CK"=>"Cook Islands",

              "CR"=>"Costa Rica",

              "CI"=>"Cote D'Ivoire",

              "HR"=>"Croatia",

              "CU"=>"Cuba",

              "CW"=>"Curaçao",

              "CY"=>"Cyprus",

              "CZ"=>"Czech Republic",

              "DK"=>"Denmark",

              "DJ"=>"Djibouti",

              "DM"=>"Dominica",

              "DO"=>"Dominican Republic",

              "EC"=>"Ecuador",

              "EG"=>"Egypt",

              "SV"=>"El Salvador",

              "GQ"=>"Equatorial Guinea",

              "ER"=>"Eritrea",

              "EE"=>"Estonia",

              "ET"=>"Ethiopia",

              "FK"=>"Falkland Islands (Malvinas)",

              "FO"=>"Faroe Islands",

              "FJ"=>"Fiji",

              "FI"=>"Finland",

              "FR"=>"France",

              "GF"=>"French Guiana",

              "PF"=>"French Polynesia",

              "TF"=>"French Southern Territories",

              "GA"=>"Gabon",

              "GM"=>"Gambia",

              "GE"=>"Georgia",

              "DE"=>"Germany",

              "GH"=>"Ghana",

              "GI"=>"Gibraltar",

              "GR"=>"Greece",

              "GL"=>"Greenland",

              "GD"=>"Grenada",

              "GP"=>"Guadeloupe",

              "GU"=>"Guam",

              "GT"=>"Guatemala",

              "GG"=>"Guernsey",

              "GN"=>"Guinea",

              "GW"=>"Guinea-Bissau",

              "GY"=>"Guyana",

              "HT"=>"Haiti",

              "HM"=>"Heard Island and McDonald Islands",

              "VA"=>"Holy See (Vatican City State)",

              "HN"=>"Honduras",

              "HK"=>"Hong Kong",

              "HU"=>"Hungary",

              "IS"=>"Iceland",

              "IN"=>"India",

              "ID"=>"Indonesia",

              "IR"=>"Iran, Islamic Republic of",

              "IQ"=>"Iraq",

              "IE"=>"Ireland",

              "IM"=>"Isle of Man",

              "IL"=>"Israel",

              "IT"=>"Italy",

              "JM"=>"Jamaica",

              "JP"=>"Japan",

              "JE"=>"Jersey",

              "JO"=>"Jordan",

              "KZ"=>"Kazakhstan",

              "KE"=>"Kenya",

              "KI"=>"Kiribati",

              "KP"=>"Korea, Democratic People's Republic of",

              "KR"=>"Korea, Republic of",

              "KW"=>"Kuwait",

              "KG"=>"Kyrgyzstan",

              "LA"=>"Lao People's Democratic Republic",

              "LV"=>"Latvia",

              "LB"=>"Lebanon",

              "LS"=>"Lesotho",

              "LR"=>"Liberia",

              "LY"=>"Libya",

              "LI"=>"Liechtenstein",

              "LT"=>"Lithuania",

              "LU"=>"Luxembourg",

              "MO"=>"Macao",

              "MK"=>"Macedonia, The Former Yugoslav Republic of",

              "MG"=>"Madagascar",

              "MW"=>"Malawi",

              "MY"=>"Malaysia",

              "MV"=>"Maldives",

              "ML"=>"Mali",

              "MT"=>"Malta",

              "MH"=>"Marshall Islands",

              "MQ"=>"Martinique",

              "MR"=>"Mauritania",

              "MU"=>"Mauritius",

              "YT"=>"Mayotte",

              "MX"=>"Mexico",

              "FM"=>"Micronesia, Federated States of",

              "MD"=>"Moldova, Republic of",

              "MC"=>"Monaco",

              "MN"=>"Mongolia",

              "ME"=>"Montenegro",

              "MS"=>"Montserrat",

              "MA"=>"Morocco",

              "MZ"=>"Mozambique",

              "MM"=>"Myanmar",

              "NA"=>"Namibia",

              "NR"=>"Nauru",

              "NP"=>"Nepal",

              "NL"=>"Netherlands",

              "NC"=>"New Caledonia",

              "NZ"=>"New Zealand",

              "NI"=>"Nicaragua",

              "NE"=>"Niger",

              "NG"=>"Nigeria",

              "NU"=>"Niue",

              "NF"=>"Norfolk Island",

              "MP"=>"Northern Mariana Islands",

              "NO"=>"Norway",

              "OM"=>"Oman",

              "PK"=>"Pakistan",

              "PW"=>"Palau",

              "PS"=>"Palestinian Territory, Occupied",

              "PA"=>"Panama",

              "PG"=>"Papua New Guinea",

              "PY"=>"Paraguay",

              "PE"=>"Peru",

              "PH"=>"Philippines",

              "PN"=>"Pitcairn",

              "PL"=>"Poland",

              "PT"=>"Portugal",

              "PR"=>"Puerto Rico",

              "QA"=>"Qatar",

              "RE"=>"Reunion",

              "RO"=>"Romania",

              "RU"=>"Russian Federation",

              "RW"=>"Rwanda",

              "BL"=>"Saint Barthelemy",

              "SH"=>"Saint Helena, Ascension and Tristan Da Cunha",

              "KN"=>"Saint Kitts and Nevis",

              "LC"=>"Saint Lucia",

              "MF"=>"Saint Martin (French part)",

              "PM"=>"Saint Pierre and Miquelon",

              "VC"=>"Saint Vincent and the Grenadines",

              "WS"=>"Samoa",

              "SM"=>"San Marino",

              "ST"=>"Sao Tome and Principe",

              "SA"=>"Saudi Arabia",

              "SN"=>"Senegal",

              "RS"=>"Serbia",

              "SC"=>"Seychelles",

              "SL"=>"Sierra Leone",

              "SG"=>"Singapore",

              "SX"=>"Sint Maarten (Dutch part)",

              "SK"=>"Slovakia",

              "SI"=>"Slovenia",

              "SB"=>"Solomon Islands",

              "SO"=>"Somalia",

              "ZA"=>"South Africa",

              "GS"=>"South Georgia and the South Sandwich Islands",

              "SS"=>"South Sudan",

              "ES"=>"Spain",

              "LK"=>"Sri Lanka",

              "SD"=>"Sudan",

              "SR"=>"Suriname",

              "SJ"=>"Svalbard and Jan Mayen",

              "SZ"=>"Swaziland",

              "SE"=>"Sweden",

              "CH"=>"Switzerland",

              "SY"=>"Syrian Arab Republic",

              "TW"=>"Taiwan, Province of China",

              "TJ"=>"Tajikistan",

              "TZ"=>"Tanzania, United Republic of",

              "TH"=>"Thailand",

              "TL"=>"Timor-Leste",

              "TG"=>"Togo",

              "TK"=>"Tokelau",

              "TO"=>"Tonga",

              "TT"=>"Trinidad and Tobago",

              "TN"=>"Tunisia",

              "TR"=>"Turkey",

              "TM"=>"Turkmenistan",

              "TC"=>"Turks and Caicos Islands",

              "TV"=>"Tuvalu",

              "UG"=>"Uganda",

              "UA"=>"Ukraine",

              "AE"=>"United Arab Emirates",

              "GB"=>"United Kingdom",

              "UM"=>"United States Minor Outlying Islands",

              "UY"=>"Uruguay",

              "UZ"=>"Uzbekistan",

              "VU"=>"Vanuatu",

              "VE"=>"Venezuela, Bolivarian Republic of",

              "VN"=>"Viet Nam",

              "VG"=>"Virgin Islands, British",

              "VI"=>"Virgin Islands, U.S.",

              "WF"=>"Wallis and Futuna",

              "EH"=>"Western Sahara",

              "YE"=>"Yemen",

              "ZM"=>"Zambia",

              "ZW"=>"Zimbabwe"

            );

    }

  }



  /**

  * Gives Name of Country with respect to the provided country code

  */

  if ( ! function_exists('getnext')) {  
  function getnext($current = '') {
    $CI =& get_instance();
    $CI->db->order_by('id', 'asc');
    $CI->db->where('id >', $current);
    $query = $CI->db->get('properties');    
    if($query->num_rows() > 0)
      return $query->row()->slug;
    else
      return FALSE;
  }
}

if ( ! function_exists('getprev')) {  
  function getprev($current = '') {
    $CI =& get_instance();
    $CI->db->order_by('id', 'desc');
    $CI->db->where('id <', $current);
    $query = $CI->db->get('properties');    
    if($query->num_rows() > 0)
      return $query->row()->slug;
    else
      return FALSE;
  }
}


if ( ! function_exists('getallpages')) {  
  function getallpages() {
    $CI =& get_instance();        
    $query = $CI->db->get('pages');    
    if($query->num_rows() > 0)
      return $query->result();
    else
      return FALSE;
  }
}


 /**
* get twitter feed
*/
if ( ! function_exists('get_twitter_feed')) { 
  function get_twitter_feed() {
   return FALSE;
   require_once APPPATH.'libraries/TwitterAPIExchange.php';  
    // $CI =& get_instance();
     $CI =& get_instance();
    $query = $CI->db->get('social_links');
    $username = $query->row()->twitter_username;

     $settings = array(
          'oauth_access_token' => "1587768884-0RkLHCvD981xkILNxtUPZzoxcIdGfohtcPCSMcR",
          'oauth_access_token_secret' => "v9CGBmwjZxCcGO735aeKE1oFQ8RnMYPB4eNPz6EJAVN5o",
          'consumer_key' => "CzVD7h4coC57VeCW3WjA",
          'consumer_secret' => "fmrsxJTjfvvOx6sogOslXZJNrxF6pgsLFlEXc6aY0"
         ); //twitter        

        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $requestMethod = 'GET';
      
      $getfield = '?screen_name='.$username.'&count=3';
      $twitter = new TwitterAPIExchange($settings);
      $response =  $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest(); //get tweet
      $tweet = json_decode($response);   
      // print_r($tweet); die();
      return $tweet;
    }    
   
    // return $CI->encrypt->decode($code);
  }

if ( ! function_exists('get_social_links')) { 
  function get_social_links() { 
    $CI =& get_instance();
    $query = $CI->db->get('social_links');
    if($query->num_rows() > 0)
      return $query->row();
    else
      return FALSE;
  }
}

if ( ! function_exists('slug_to_id')) { 
  function slug_to_id($tablename='', $slug='') { 
    $CI =& get_instance();
    $CI->db->from($tablename);
    $CI->db->where('slug', $slug);
    $query = $CI->db->get();
    if($query->num_rows() > 0)
      return $query->row()->id;
    else
      return 0;
  }
}

if ( ! function_exists('id_to_slug')) { 
  function id_to_slug($tablename='', $id='') { 
    $CI =& get_instance();
    $CI->db->from($tablename);
    $CI->db->where('id', $id);
    $query = $CI->db->get();
    if($query->num_rows() > 0)
      return $query->row()->slug;
    else
      return 0;
  }
}


if ( ! function_exists('subgroup_id_to_name')) { 
  function subgroup_id_to_name($id='') { 
    $CI =& get_instance();    
    $CI->db->where('id', $id);
    $query = $CI->db->get('sub_group');
    if($query->num_rows() > 0)
      return $query->row()->name;
    else
      return 0;
  }
}

if ( ! function_exists('createRandomPassword')) { 
  function createRandomPassword($length = 5) {
    $chars = "abcdefghijkmnopqrstuvwxyz023456789@#$";
    srand((double)microtime()*1000000);
    $i = 0;
    $pass = '' ;
    while ($i < $length) {
      $num = rand() % 36;
      $tmp = substr($chars, $num, 1);
      $pass = $pass . $tmp;
      $i++;
    }
    return strtoupper($pass);
  }
}

if ( ! function_exists('subgroup_id_to_name')) { 
  function subgroup_id_to_name($id='') { 
    $CI =& get_instance();    
    $CI->db->where('id', $id);
    $query = $CI->db->get('sub_group');
    if($query->num_rows() > 0)
      return $query->row()->name;
    else
      return 0;
  }
}


if ( ! function_exists('get_trainer_name')) {  
  function get_trainer_name() {
    $CI =& get_instance();
    $user_info=$CI->session->userdata('trainerInfo');    
    return $user_info['fname'].' '.$user_info['lname'];
  }
}


if ( ! function_exists('get_trainer_info')) {  

  function get_trainer_info() {
    $CI =& get_instance();
    $user_info=$CI->session->userdata('trainerInfo');    
    $CI->db->where('id', $user_info['id']);
    $query = $CI->db->get('trainer');
      if($query->num_rows() > 0)
        return $query->row();
      else
        return FALSE;
  }

}


if ( ! function_exists('get_trainee_info')) {  

  function get_trainee_info() {
    $CI =& get_instance();
    $user_info=$CI->session->userdata('traineeInfo');    
    $CI->db->where('id', $user_info['id']);
    $query = $CI->db->get('trainee');
      if($query->num_rows() > 0)
        return $query->row();
      else
        return FALSE;
  }
}

if ( ! function_exists('trainee_workout')){  
  function trainee_workout($id){
    $CI =& get_instance();       
    $CI->load->model('admin_model');
    $workout = $CI->admin_model->get_trainee_workout($id);
    $status_array = array();
    if($workout){
      foreach($workout as $row){
        $namee = $row->name;
        $namee = explode(' ', $row->name);
        if(count($namee) > 2){
          $namee = $namee[0].' '.$namee[1];
        }
        else{
          $namee = implode(' ', $namee);
        }

        $date = strtotime($row->date);
        if(isset($status_array[$date])){
          if($status_array[$date] == 1){
            $arr[] = array(
              'className'    => $row->id,
              'title' => ucfirst(strtolower(remove_special_character($namee))),
              'start' => date("Y-m-d", $date),
            );
            $status_array[$date] = 2;
          }elseif($status_array[$date] == 2){
            $arr[] = array(
              'className'    => $row->id,
              'title' => 'zzzzzzz.....',
              'start' => date("Y-m-d", $date),
            );
            $status_array[$date] = 3;
          }
        }else{
          $arr[] = array(
            'className'    => $row->id,
            'title' => ucfirst(strtolower(remove_special_character($namee))),
            'start' => date("Y-m-d", $date),
          );
          $status_array[$date] = 1;
        }
      }      
      return json_encode($arr);
    }else{
      return FALSE;
    }
  }
}


/**
* create Image from base64 code
*/
if ( ! function_exists('createImage')) {
  function createImage($base64_string = 0){
    if($base64_string == '0')
      return FALSE;

    $CI =& get_instance();
    $base64_decode = base64_decode($base64_string);
    // var_dump($base64_string); die();
    // $img = imagecreatefromjpeg( "data:image/jpeg;base64,".$base64_string );
    $img = imagecreatefromstring($base64_decode);
      $width = imagesx( $img );
      $height = imagesy( $img );
      $new_width = $width;
      $new_height = $height;
      $tmp_img = imagecreatetruecolor( $new_width, $new_height );
      imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

      while(1){
      $arr = str_split('Aa2B3b4C5c6D7d8E9eFfGgHhJjKkMmNnOoPpQqRrSsTtUuVvWwXxYyZz');
      shuffle($arr);
      $arr = array_slice($arr, 0, 10);
      $str = implode('', $arr);
      $str = $str."-".time().".jpg";
      $query = $CI->db->get_where('trainee', array('image' => $str));
      if($query->num_rows() == 0)
        break;
    }

      imagejpeg( $tmp_img, "assets/uploads/trainee/".$str );

      return $str;
  }
}

if ( ! function_exists('createImageOnAws')) {
  function createImageOnAws($base64_string = 0){
    if($base64_string == '0')
      return FALSE;

    $CI =& get_instance();
    $base64_decode = base64_decode($base64_string);
    // var_dump($base64_string); die();
    // $img = imagecreatefromjpeg( "data:image/jpeg;base64,".$base64_string );
    $img = imagecreatefromstring($base64_decode);
      $width = imagesx( $img );
      $height = imagesy( $img );
      $new_width = $width;
      $new_height = $height;
      $tmp_img = imagecreatetruecolor( $new_width, $new_height );
      imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

      while(1){
      $arr = str_split('Aa2B3b4C5c6D7d8E9eFfGgHhJjKkMmNnOoPpQqRrSsTtUuVvWwXxYyZz');
      shuffle($arr);
      $arr = array_slice($arr, 0, 10);
      $str = implode('', $arr);
      $str = 't_'.$str."-".time().".jpg";
      $query = $CI->db->get_where('trainee', array('image' => $str));
      if($query->num_rows() == 0)
        break;
    }

      imagejpeg( $tmp_img, "../tmp/".$str );

      if(upload_to_bucket_server_from_app($str)){
        return $str;
      }
      else{
        return FALSE;
      }
  }
}

if ( ! function_exists('get_token')) {
  function get_token() {
    $length = 100;
    $characters = "0123456789abcdefghijklmnopqrstuvwxyz".time()."0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ".time();
    $string = "";    
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters)-1)];
    }
    return $string;
  }
}

if ( ! function_exists('cms_current_url')) { 
  function cms_current_url() { 
    $CI =& get_instance();
    return base_url()._INDEX.$CI->uri->uri_string();
  }
}


  function get_workout_full_detail($workout_id) { 
    $CI =& get_instance();
    $CI->load->model('admin_model');
    $data = array();
    $workout = $CI->admin_model->get_row('workout', array('id' => $workout_id));

    $data['date'] = date("m/d/Y", strtotime($workout->date));
    $data['time'] = date("h:i A", strtotime($workout->time));
    $data['trainee_id'] = $workout->trainee_id;
    $data['name'] = $workout->name;
    $data['image'] = $workout->image;
    $data['status'] = $workout->status;
    $data['description'] = $workout->description;
    $exercise = $CI->admin_model->get_result('exercise', array('workout_id' => $workout_id));

    $data['count_of_exercise'] = count($exercise);

       if(!empty($exercise))
       {
      foreach($exercise as $row)
      {
        $arr = array();
        $arr['exercise'] = $row->name;
        $arr['description'] = $row->description;
        $arr['image'] = $row->image;
        $arr['resttime'] = $row->resttime;
        $arr['exercise'] = $row->name;
        $arr['exercise_id'] = $row->id;
        $arr['exercise_status'] = $row->status;
        $set = $CI->admin_model->get_result('exercise_set', array('exercise_id' => $row->id));
        $arr['sets'] = $set;
        $data['exercises'][] = $arr;
      }
       }

    return $data;
  }

  function get_last_workout($trainee_id=""){
    $CI  =&get_instance();    
    $CI->db->select('wk.*');
    $CI->db->from('workout wk');
    $CI->db->join('trainee_workout as twk', 'twk.workout_id = wk.id','left');
    $CI->db->where('wk.date <=',date('Y-m-d'));
    $CI->db->where('twk.trainee_id',$trainee_id);
    $CI->db->limit(1);
    $CI->db->order_by('wk.date','desc');
    $query=$CI->db->get();
    if($query->num_rows()>0){
      return $query->row();
    }
    else
      return FALSE;
  }

  function get_workout_status($workout_id){
   $exist = get_result('exercise', array('workout_id'=>$workout_id, 'status'=>0));
   if($exist){
      return FALSE;
   }else{
      return TRUE;
   }

  }

  function get_next_workout($trainee_id=""){
    $CI  =&get_instance();    
    $CI->db->select('wk.*');
    $CI->db->from('workout wk');
    $CI->db->join('trainee_workout as twk', 'twk.workout_id = wk.id','left');
    $CI->db->where('wk.date >',date('Y-m-d'));
    $CI->db->where('twk.trainee_id',$trainee_id);
    $CI->db->limit(1);
    $CI->db->order_by('wk.date','asc');
    $query=$CI->db->get();
    if($query->num_rows()>0){
      return $query->row();
    }
    else
      return FALSE;
  }




function count_replies($token="",$where=""){
    $CI  =&get_instance();    
    $CI->db->where('support_token2',$token);

    $CI->db->where($where,0);

    $query=$CI->db->get('conversation');
    if($query->num_rows()>0)
      return $query->num_rows();
    else
      return FALSE;
}

function get_count($table_name,$where_array){
  $CI =& get_instance();
  $CI->db->where($where_array);
    $query = $CI->db->get($table_name);
    return $query->num_rows();
}

function count_unread_support(){
    $CI  =&get_instance();    
    $trainer_id = get_trainer_id();
    

    $trainer_result = get_result('support',array('trainer_id'=>$trainer_id));
    $i=0; 
    if($trainer_result){
      foreach($trainer_result as $row){
        $is_exist = get_result('conversation',array('support_token2'=>$row->token2,'trainer_read'=>0));
        if($is_exist){
          $i++;
        }
      }
    }
    return $i;
  }

function seconds_to_string($seconds = 0){
  switch($seconds){
    case ( $seconds == 1 ) : return $seconds.' second'; break;
    case ( $seconds <= 59 ) : return $seconds.' seconds'; break;
    case ( $seconds > 59 ) : 
      $min = (int)($seconds / 60);
      $sec = $seconds % 60;
      
      $res = '';
      $res .= $min;
      if( $min == 1 )
        $res .= ' minute ';
      else
        $res .= ' minutes ';

      if($sec > 0){

        $res .= $sec;

        if( $sec == 1 )
          $res .= ' second';
        else
          $res .= ' seconds';

      }

      return $res;
      
      break;
    default : return $seconds; break;
  }
}

function get_trainee_id_set_by_trainer(){
  $CI =& get_instance();
  return $CI->session->userdata('trainee_id_set_by_trainer'); 
}