<?php
/**
 * @author Gurdeep Singh  <gurdeepsingh03@gmail.com>
 * This is the view to register a new institution.
 */
include '../../template/infinitymetrics-bootstrap.php';

#----------------------------->>>>>>>>>>>>> Controller Usage for UC404 ----------------------------->>>>>>>>>>>>>>>

if (isset($_POST["inst_name"]) && isset($_POST["inst_abbv"]) && isset($_POST["city"])
                 && isset($_POST["state"]) && isset($_POST["country"])) {

    require_once 'infinitymetrics/controller/UserManagementController.class.php';
    require_once 'infinitymetrics/model/InfinityMetricsException.class.php';

 /*   $inst = PersistentInstitutionPeer::retrieveByAbbreviation($_POST["inst_abbv"]);
     if($inst != null) {
        $errors = array();
                $errors["institutionAlreadyRegistered"] = "The institution referred by " . $_POST["inst_abbv"] . " already exists";
                throw new InfinityMetricsException(" Instituion is already registered with Infinity Metrics", $errors);
     }else {
*/
   try {
       $institution = UserManagementController::registerInstitution($_POST["inst_name"],$_POST["inst_abbv"],$_POST["city"],
                 $_POST["state"],$_POST["country"]);
          header('Location: signup-step2.php?createdInstitution =$institution->getInstitutionId();');
    } catch (Exception $e) {
        $_SESSION["signupError"] = $e;
    }
 
}

#----------------------------->>>>>>>>>>>>> Variables Initialization ------------------->>>>>>>>>>>>>>>



$subUseCase = "Register Institution ";
    $enableLeftNav = true;

    #breadscrum[URL] = Title
    $breakscrum = array(
                        $_SERVER["home_address"] => "Home",
                        $_SERVER["home_address"]."/user" => "Users Registration",
                        $_SERVER["home_address"]."/user/student/signup-step1.php" => "1. Java.net Authentication",
                		$_SERVER["home_address"]."/user/student/signup-step2.php" => "2. Profile Update",
                        $_SERVER["home_address"]."/user/institution/signup-step2a.php" => "2a. Institution Registration",
                  );

    #leftMenu[n]["active"] - If the menu item is active or not
    #leftMenu[n]["url"] - the URL for the menu item
    #leftMenu[n]["item"] - the item of the menu
    #leftMenu[n]["tip"] - the tooltip of the URL
    $leftMenu = array();
    array_push($leftMenu, array("active"=>"menu-27", "url"=>"signup-step1.php", "item"=>"1. Java.net Authentication", "tip"=>"Manage your site's book outlines."));
    array_push($leftMenu, array("active"=>"menu-27 first active", "url"=>"signup-step2.php", "item"=>"2. Update Profile", "tip"=>"Update and review your profile info"));
    array_push($leftMenu, array("active"=>"menu-27 first active", "url"=>"signup-step2a.php", "item"=>"2a. Register Institution ", "tip"=>"Register your institution"));
    array_push($leftMenu, array("active"=>"menu-27", "url"=>"signup-step3.php", "item"=>"3. Confirm Registration", "tip"=>"Confirm you profile"));

?>

<?php
//session_start();
//session_register("user");
//$user = $HTTP_POST_VARS['username'];

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html class="js" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>
<title>Infinity Metrics: <?php echo $subUseCase; ?></title>

<?php include 'static-js-css.php';  ?>

<?php include 'user-signup-header-adds.php' ?>

</head>
<body class="<?php echo $enableLeftNav ? $leftNavClass : $NoLeftNavClass; ?>">

<?php  include 'top-navigation.php';  ?>

                  <div id="breadcrumb" class="alone">
                    <h2 id="title">Home</h2>
                    <div class="breadcrumb">
<?php
                        $totalBreadscrum = count(array_keys($breakscrum)); $idx = 0;
                        foreach (array_keys($breakscrum) as $keyUrl) {
                              echo "<a href=\"" . $keyUrl . "\"> " . $breakscrum[$keyUrl] . "</a> ".
                                (++$idx < $totalBreadscrum ? "» " : " ");
                        }
?>
                    </div>
                  </div>

                  <div id="content-wrap">
                    <div id="inside">

                        <div id="sidebar-left">
                            <ul id="rootcandy-menu">
                                <?php
                                foreach ($leftMenu as $menuItem) {
                                    echo "<li class=\"".$menuItem["active"]."\"><a href=\"".$menuItem["url"]."\"
                                              title=\"".$menuItem["tip"]."\">".$menuItem["item"]."</a></li>";
                                }
                                ?>
                            </ul>
                        </div><!-- end sidebar-left -->

                        <div id="sidebar-right">
                          <div id="block-user-3" class="block block-user">
                              <h2>All users are welcomed</h2>
                              <div class="content" align="center">
                                <img src="../../template/images/techglobe2.jpg">
                              </div>
                          </div>
                        </div>

                    </div>
                    <div id="content">
<div class="t"><div class="b"><div class="l"><div class="r"><div class="bl"><div class="br"><div class="tl"><div class="tr"><div class="content-in">

<div>
<div class="node-form">

      		<form id="signupform" autocomplete="off" method="post" action="<?php echo $PHP_SELF ?>">
<?php
        if (isset($_SESSION["signupError"]) && $_SESSION["signupError"] != "") {
             echo "<div class=\"messages error\">".$_SESSION["signupError"]."</div>";
             $_SESSION["signupError"] = "";
             unset($_SESSION["signupError"]);
        }
?>
	  		  <table align="center">
	  		  <tbody>
	  		  <tr>
	  			<td class="status" width="50">&nbsp;</td>
	  			<td class="label" width="20"><label id="linst_name" for="institutionName">Institution</label></td>
	  			<td class="field"><input id="inst_name" name="inst_name" class="textfield" value="" maxlength="50" type="text"></td>
                
	  		  <tr>
	  			<td class="status"></td>
	  			<td class="label"><label id="linst_abbv" for="institutionAbbreviation">Abbreviation</label></td>
	  			<td class="field"><input id="inst_abbv" name="inst_abbv" class="textfield" maxlength="50" value="" type="text"></td>
	  		  </tr>

              <tr>
	  			<td class="status"></td>
	  			<td class="label"><label id="lcity" for="city">City</label></td>
	  			<td class="field"><input id="city" name="city" class="textfield" maxlength="50" value="" type="text"></td>
	  		  </tr>

              <tr>
	  			<td class="status"></td>
	  			<td class="label"><label id="lstate" for="stateProvince">State/Province</label></td>
	  			<td class="field"><input id="state" name="state" class="textfield" maxlength="50" value="" type="text"></td>
	  		  </tr>

              <tr>
	  			<td class="status"></td>
                 <td class="label"><label id="lcountry" for="country">Country</label></td>
	  			<td>
       <noscript>         
       <input id ="country" type="hidden" name="country" class="textfield" value="en">
       </noscript>
  <select id="country" name="country"
            onchange="javascript:reloadTos(reloadTimeZones)" >
  <option value="AF"

          >
  Afghanistan (افغانستان)
  </option>
  <option value="AX"

          >
  Aland Islands
  </option>
  <option value="AL"

          >
  Albania (Shqipëria)
  </option>
  <option value="DZ"

          >
  Algeria (الجزائر)
  </option>
  <option value="AS"

          >
  American Samoa
  </option>
  <option value="AD"

          >
  Andorra
  </option>
  <option value="AO"

          >
  Angola
  </option>
  <option value="AI"

          >
  Anguilla
  </option>
  <option value="AQ"

          >
  Antarctica
  </option>
  <option value="AG"

          >
  Antigua and Barbuda
  </option>
  <option value="AR"

          >
  Argentina
  </option>
  <option value="AM"

          >
  Armenia (Հայաստան)
  </option>
  <option value="AW"

          >
  Aruba
  </option>
  <option value="AU"

          >
  Australia
  </option>
  <option value="AT"

          >
  Austria (Österreich)
  </option>
  <option value="AZ"

          >
  Azerbaijan (Azərbaycan)
  </option>
  <option value="BS"

          >
  Bahamas
  </option>
  <option value="BH"

          >
  Bahrain (البحرين)
  </option>
  <option value="BD"

          >
  Bangladesh (বাংলাদেশ)
  </option>
  <option value="BB"

          >
  Barbados
  </option>
  <option value="BY"

          >
  Belarus (Белару́сь)
  </option>
  <option value="BE"

          >
  Belgium (België)
  </option>
  <option value="BZ"

          >
  Belize
  </option>
  <option value="BJ"

          >
  Benin (Bénin)
  </option>
  <option value="BM"

          >
  Bermuda
  </option>
  <option value="BT"

          >
  Bhutan (འབྲུག་ཡུལ)
  </option>
  <option value="BO"

          >
  Bolivia
  </option>
  <option value="BA"

          >
  Bosnia and Herzegovina (Bosna i Hercegovina)
  </option>
  <option value="BW"

          >
  Botswana
  </option>
  <option value="BV"

          >
  Bouvet Island
  </option>
  <option value="BR"

          >
  Brazil (Brasil)
  </option>
  <option value="IO"

          >
  British Indian Ocean Territory
  </option>
  <option value="BN"

          >
  Brunei (Brunei Darussalam)
  </option>
  <option value="BG"

          >
  Bulgaria (България)
  </option>
  <option value="BF"

          >
  Burkina Faso
  </option>
  <option value="BI"

          >
  Burundi (Uburundi)
  </option>
  <option value="KH"

          >
  Cambodia (Kampuchea)
  </option>
  <option value="CM"

          >
  Cameroon (Cameroun)
  </option>
  <option value="CA"

          >
  Canada
  </option>
  <option value="CV"

          >
  Cape Verde (Cabo Verde)
  </option>
  <option value="KY"

          >
  Cayman Islands
  </option>
  <option value="CF"

          >
  Central African Republic (République Centrafricaine)
  </option>
  <option value="TD"

          >
  Chad (Tchad)
  </option>
  <option value="CL"

          >
  Chile
  </option>
  <option value="CN"

          >
  China (中国)
  </option>
  <option value="CX"

          >
  Christmas Island
  </option>
  <option value="CC"

          >
  Cocos Islands
  </option>
  <option value="CO"

          >
  Colombia
  </option>
  <option value="KM"

          >
  Comoros (Comores)
  </option>
  <option value="CG"

          >
  Congo
  </option>
  <option value="CD"

          >
  Congo, Democratic Republic of the
  </option>
  <option value="CK"

          >
  Cook Islands
  </option>
  <option value="CR"

          >
  Costa Rica
  </option>
  <option value="CI"

          >
  Côte d&#39;Ivoire
  </option>
  <option value="HR"

          >
  Croatia (Hrvatska)
  </option>
  <option value="CU"

          >
  Cuba
  </option>
  <option value="CY"

          >
  Cyprus (Κυπρος)
  </option>
  <option value="CZ"

          >
  Czech Republic (Česko)
  </option>
  <option value="DK"

          >
  Denmark (Danmark)
  </option>
  <option value="DJ"

          >
  Djibouti
  </option>
  <option value="DM"

          >
  Dominica
  </option>
  <option value="DO"

          >
  Dominican Republic
  </option>
  <option value="EC"

          >
  Ecuador
  </option>
  <option value="EG"

          >
  Egypt (مصر)
  </option>
  <option value="SV"

          >
  El Salvador
  </option>
  <option value="GQ"

          >
  Equatorial Guinea (Guinea Ecuatorial)
  </option>
  <option value="ER"

          >
  Eritrea (Ertra)
  </option>
  <option value="EE"

          >
  Estonia (Eesti)
  </option>
  <option value="ET"

          >
  Ethiopia (Ityop&#39;iya)
  </option>
  <option value="FK"

          >
  Falkland Islands
  </option>
  <option value="FO"

          >
  Faroe Islands
  </option>
  <option value="FJ"

          >
  Fiji
  </option>
  <option value="FI"

          >
  Finland (Suomi)
  </option>
  <option value="FR"

          >
  France
  </option>
  <option value="GF"

          >
  French Guiana
  </option>
  <option value="PF"

          >
  French Polynesia
  </option>
  <option value="TF"

          >
  French Southern Territories
  </option>
  <option value="GA"

          >
  Gabon
  </option>
  <option value="GM"

          >
  Gambia
  </option>
  <option value="GE"

          >
  Georgia (საქართველო)
  </option>
  <option value="DE"

          >
  Germany (Deutschland)
  </option>
  <option value="GH"

          >
  Ghana
  </option>
  <option value="GI"

          >
  Gibraltar
  </option>
  <option value="GR"

          >
  Greece (Ελλάς)
  </option>
  <option value="GL"

          >
  Greenland
  </option>
  <option value="GD"

          >
  Grenada
  </option>
  <option value="GP"

          >
  Guadeloupe
  </option>
  <option value="GU"

          >
  Guam
  </option>
  <option value="GT"

          >
  Guatemala
  </option>
  <option value="GG"

          >
  Guernsey
  </option>
  <option value="GN"

          >
  Guinea (Guinée)
  </option>
  <option value="GW"

          >
  Guinea-Bissau (Guiné-Bissau)
  </option>
  <option value="GY"

          >
  Guyana
  </option>
  <option value="HT"

          >
  Haiti (Haïti)
  </option>
  <option value="HM"

          >
  Heard Island and McDonald Islands
  </option>
  <option value="HN"

          >
  Honduras
  </option>
  <option value="HK"

          >
  Hong Kong
  </option>
  <option value="HU"

          >
  Hungary (Magyarország)
  </option>
  <option value="IS"

          >
  Iceland (Ísland)
  </option>
  <option value="IN"

          >
  India
  </option>
  <option value="ID"

          >
  Indonesia
  </option>
  <option value="IR"

          >
  Iran (ایران)
  </option>
  <option value="IQ"

          >
  Iraq (العراق)
  </option>
  <option value="IE"

          >
  Ireland
  </option>
  <option value="IM"

          >
  Isle of Man
  </option>
  <option value="IL"

          >
  Israel (ישראל)
  </option>
  <option value="IT"

          >
  Italy (Italia)
  </option>
  <option value="JM"

          >
  Jamaica
  </option>
  <option value="JP"

          >
  Japan (日本)
  </option>
  <option value="JE"

          >
  Jersey
  </option>
  <option value="JO"

          >
  Jordan (الاردن)
  </option>
  <option value="KZ"

          >
  Kazakhstan (Қазақстан)
  </option>
  <option value="KE"

          >
  Kenya
  </option>
  <option value="KI"

          >
  Kiribati
  </option>
  <option value="KW"

          >
  Kuwait (الكويت)
  </option>
  <option value="KG"

          >
  Kyrgyzstan (Кыргызстан)
  </option>
  <option value="LA"

          >
  Laos (ນລາວ)
  </option>
  <option value="LV"

          >
  Latvia (Latvija)
  </option>
  <option value="LB"

          >
  Lebanon (لبنان)
  </option>
  <option value="LS"

          >
  Lesotho
  </option>
  <option value="LR"

          >
  Liberia
  </option>
  <option value="LY"

          >
  Libya (ليبيا)
  </option>
  <option value="LI"

          >
  Liechtenstein
  </option>
  <option value="LT"

          >
  Lithuania (Lietuva)
  </option>
  <option value="LU"

          >
  Luxembourg (Lëtzebuerg)
  </option>
  <option value="MO"

          >
  Macao
  </option>
  <option value="MK"

          >
  Macedonia (Македонија)
  </option>
  <option value="MG"

          >
  Madagascar (Madagasikara)
  </option>
  <option value="MW"

          >
  Malawi
  </option>
  <option value="MY"

          >
  Malaysia
  </option>
  <option value="MV"

          >
  Maldives (ގުޖޭއްރާ ޔާއްރިހޫމްޖ)
  </option>
  <option value="ML"

          >
  Mali
  </option>
  <option value="MT"

          >
  Malta
  </option>
  <option value="MH"

          >
  Marshall Islands
  </option>
  <option value="MQ"

          >
  Martinique
  </option>
  <option value="MR"

          >
  Mauritania (موريتانيا)
  </option>
  <option value="MU"

          >
  Mauritius
  </option>
  <option value="YT"

          >
  Mayotte
  </option>
  <option value="MX"

          >
  Mexico (México)
  </option>
  <option value="FM"

          >
  Micronesia
  </option>
  <option value="MD"

          >
  Moldova
  </option>
  <option value="MC"

          >
  Monaco
  </option>
  <option value="MN"

          >
  Mongolia (Монгол Улс)
  </option>
  <option value="ME"

          >
  Montenegro (Црна Гора)
  </option>
  <option value="MS"

          >
  Montserrat
  </option>
  <option value="MA"

          >
  Morocco (المغرب)
  </option>
  <option value="MZ"

          >
  Mozambique (Moçambique)
  </option>
  <option value="MM"

          >
  Myanmar (Burma)
  </option>
  <option value="NA"

          >
  Namibia
  </option>
  <option value="NR"

          >
  Nauru (Naoero)
  </option>
  <option value="NP"

          >
  Nepal (नेपाल)
  </option>
  <option value="NL"

          >
  Netherlands (Nederland)
  </option>
  <option value="AN"

          >
  Netherlands Antilles
  </option>
  <option value="NC"

          >
  New Caledonia
  </option>
  <option value="NZ"

          >
  New Zealand
  </option>
  <option value="NI"

          >
  Nicaragua
  </option>
  <option value="NE"

          >
  Niger
  </option>
  <option value="NG"

          >
  Nigeria
  </option>
  <option value="NU"

          >
  Niue
  </option>
  <option value="NF"

          >
  Norfolk Island
  </option>
  <option value="MP"

          >
  Northern Mariana Islands
  </option>
  <option value="KP"

          >
  North Korea (조선)
  </option>
  <option value="NO"

          >
  Norway (Norge)
  </option>
  <option value="OM"

          >
  Oman (عمان)
  </option>
  <option value="PK"

          >
  Pakistan (پاکستان)
  </option>
  <option value="PW"

          >
  Palau (Belau)
  </option>
  <option value="PS"

          >
  Palestinian Territory
  </option>
  <option value="PA"

          >
  Panama (Panamá)
  </option>
  <option value="PG"

          >
  Papua New Guinea
  </option>
  <option value="PY"

          >
  Paraguay
  </option>
  <option value="PE"

          >
  Peru (Perú)
  </option>
  <option value="PH"

          >
  Philippines (Pilipinas)
  </option>
  <option value="PN"

          >
  Pitcairn
  </option>
  <option value="PL"

          >
  Poland (Polska)
  </option>
  <option value="PT"

          >
  Portugal
  </option>
  <option value="PR"

          >
  Puerto Rico
  </option>
  <option value="QA"

          >
  Qatar (قطر)
  </option>
  <option value="RE"

          >
  Reunion
  </option>
  <option value="RO"

          >
  Romania (România)
  </option>
  <option value="RU"

          >
  Russia (Россия)
  </option>
  <option value="RW"

          >
  Rwanda
  </option>
  <option value="SH"

          >
  Saint Helena
  </option>
  <option value="KN"

          >
  Saint Kitts and Nevis
  </option>
  <option value="LC"

          >
  Saint Lucia
  </option>
  <option value="PM"

          >
  Saint Pierre and Miquelon
  </option>
  <option value="VC"

          >
  Saint Vincent and the Grenadines
  </option>
  <option value="WS"

          >
  Samoa
  </option>
  <option value="SM"

          >
  San Marino
  </option>
  <option value="ST"

          >
  São Tomé and Príncipe
  </option>
  <option value="SA"

          >
  Saudi Arabia (المملكة العربية السعودية)
  </option>
  <option value="SN"

          >
  Senegal (Sénégal)
  </option>
  <option value="RS"

          >
  Serbia (Србија)
  </option>
  <option value="CS"

          >
  Serbia and Montenegro (Србија и Црна Гора)
  </option>
  <option value="SC"

          >
  Seychelles
  </option>
  <option value="SL"

          >
  Sierra Leone
  </option>
  <option value="SG"

          >
  Singapore (Singapura)
  </option>
  <option value="SK"

          >
  Slovakia (Slovensko)
  </option>
  <option value="SI"

          >
  Slovenia (Slovenija)
  </option>
  <option value="SB"

          >
  Solomon Islands
  </option>
  <option value="SO"

          >
  Somalia (Soomaaliya)
  </option>
  <option value="ZA"

          >
  South Africa
  </option>
  <option value="GS"

          >
  South Georgia and the South Sandwich Islands
  </option>
  <option value="KR"

          >
  South Korea
  </option>
  <option value="ES"

          >
  Spain (España)
  </option>
  <option value="LK"

          >
  Sri Lanka
  </option>
  <option value="SD"

          >
  Sudan (السودان)
  </option>
  <option value="SR"

          >
  Suriname
  </option>
  <option value="SJ"

          >
  Svalbard and Jan Mayen
  </option>
  <option value="SZ"

          >
  Swaziland
  </option>
  <option value="SE"

          >
  Sweden (Sverige)
  </option>
  <option value="CH"

          >
  Switzerland (Schweiz)
  </option>
  <option value="SY"

          >
  Syria (سوريا)
  </option>
  <option value="TW"

          >
  Taiwan (台灣)
  </option>
  <option value="TJ"

          >
  Tajikistan (Тоҷикистон)
  </option>
  <option value="TZ"

          >
  Tanzania
  </option>
  <option value="TH"

          >
  Thailand (ราชอาณาจักรไทย)
  </option>
  <option value="TL"

          >
  Timor-Leste
  </option>
  <option value="TG"

          >
  Togo
  </option>
  <option value="TK"

          >
  Tokelau
  </option>
  <option value="TO"

          >
  Tonga
  </option>
  <option value="TT"

          >
  Trinidad and Tobago
  </option>
  <option value="TN"

          >
  Tunisia (تونس)
  </option>
  <option value="TR"

          >
  Turkey (Türkiye)
  </option>
  <option value="TM"

          >
  Turkmenistan (Türkmenistan)
  </option>
  <option value="TC"

          >
  Turks and Caicos Islands
  </option>
  <option value="TV"

          >
  Tuvalu
  </option>
  <option value="UG"

          >
  Uganda
  </option>
  <option value="UA"

          >
  Ukraine (Україна)
  </option>
  <option value="AE"

          >
  United Arab Emirates (الإمارات العربيّة المتّحدة)
  </option>
  <option value="GB"

          >
  United Kingdom
  </option>
  <option value="US"

            selected

          >
  United States
  </option>
  <option value="UM"

          >
  United States minor outlying islands
  </option>
  <option value="UY"

          >
  Uruguay
  </option>
  <option value="UZ"

          >
  Uzbekistan (O&#39;zbekiston)
  </option>
  <option value="VU"

          >
  Vanuatu
  </option>
  <option value="VA"

          >
  Vatican City (Città del Vaticano)
  </option>
  <option value="VE"

          >
  Venezuela
  </option>
  <option value="VN"

          >
  Vietnam (Việt Nam)
  </option>
  <option value="VG"

          >
  Virgin Islands, British
  </option>
  <option value="VI"

          >
  Virgin Islands, U.S.
  </option>
  <option value="WF"

          >
  Wallis and Futuna
  </option>
  <option value="EH"

          >
  Western Sahara (الصحراء الغربية)
  </option>
  <option value="YE"

          >
  Yemen (اليمن)
  </option>
  <option value="ZM"

          >
  Zambia
  </option>
  <option value="ZW"

          >
  Zimbabwe
  </option>
  </select>
  <noscript>
  <input type="submit" name="country" id="country"
                   style="display:none" />
    </noscript>

 
	  			
	  		  </tr>
              <td>
  </td>
</tr>



              <tr>
                <td>&nbsp;</td>
                <td colspan="2" align="center">
                    <input id="edit-submit" value="Register Institution" class="form-submit" type="submit" onclick="document.location = signup-step2.php">
                    <input id="edit-delete" value="Cancel" class="form-submit" type="button" onclick="document.location=signup-step2.php">
                </td>
              </tr>
	  		  </tbody></table>
        </form>
</div>
</div> <!-- End of blue box -->

    </div><br class="clear"></div></div></div></div></div></div></div></div>

<!-- End section -->


          </div>
        </div>

          </div>

<?php include 'footer.php';   ?>