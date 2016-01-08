<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * English strings for mapletadp
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_mapletadp
 * @copyright  2015 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['modulename'] = 'Maple T.A.';
$string['modulenameplural'] = 'Maple T.A. ';
$string['modulename_help'] = 'Vlastní Maple T.A. konektor komunikujicí přes webové služby. Navržený pro diplomovou práci.';
$string['mapletadpfieldset'] = 'Custom example fieldset';
$string['mapletadpname'] = 'Název';
$string['mapletadpname_help'] = 'Zadejte jméno modulu Maple T.A. v rámci kurzu.';
$string['mapletadp'] = 'Maple T.A. - vlastní';
$string['pluginadministration'] = 'Vlastní Maple T.A. - administrace';
$string['pluginname'] = 'Maple T.A.';

$string['assignmentName'] = 'Jméno úkolu';
$string['className'] = 'Jméno třídy';
$string['choose'] = ' -- Vyberte třídu --';
$string['selectAssignment'] = 'Musíte vybrat úkol';
$string['selectClass'] = 'Musíte vybrat třídu';


//configuration

$string['protocol'] = 'Protokol';
$string['protocoldescription'] = 'Vyberte podle toho, na kterém protokolu běží instalace Maple T.A.';
$string['server'] = 'Jméno serveru';
$string['serverdescription'] = 'Napište adresu serveru s instalací Maple T.A. tak, jak ji zadáváte do prohlížeče včetně portu (je-li uveden).';
$string['context'] = 'Kontext (část za prvním lomítkem v adrese)';
$string['contextdescription'] = 'Při zadání adresy do prohlížeče zadáváta tzv. kontext, tedy část za prvním lomítkem.';
$string['secret'] = 'Sdílené heslo';
$string['secretdescription'] = 'Heslo je vlastní a tajné pro šifrování a dešifrování komunikace mezi Moodle <--> Maple T.A.';
$string['timeout'] = 'Životnost SessionID';
$string['timeoutdescription'] = 'Tímto parametrem nastavíte dobu, po jakou bude udržováno SessionID od posledního použití (v minutáchů maximálně 200minut).';

$string['availableassignments'] = 'Dostupné Maple T.A.';

$string['synchronization'] = 'List synchronization';
$string['startassignment'] = 'Spustit úkol';
$string['waitplease'] = 'Počkejte prosím - načítá se úkol.';

$string['notmonitored'] = 'Webové služby nebyli provolány.';

$string['settingsheading'] = 'Nastavení připojení k Maple T.A.';
$string['settingsinfo'] = 'Nastavte, prosím, parametry pro připojení k Maple T.A. a parametry rozšíření.';

$string['mode'] = 'Typ úkolu';
$string['mode0'] = 'Zkouška s dozorem';
$string['mode1'] = 'Domácí úkol nebo test';
$string['mode2'] = 'Procvičování';
$string['mode3'] = 'Mastery úkol';
$string['mode4'] = 'Hodina';

$string['totalpoints'] = 'Celkový počet bodů';
$string['passingscore'] = 'Minimální počet bodů pro splnění';
$string['start'] = 'Začátek úlohy';
$string['end'] = 'Konec úlohy';
$string['timelimit'] = 'Časový limit (v minutách)';
$string['notsetvalue'] = 'Nenastaveno';

$string['no'] = 'Ne';
$string['yes'] = 'Ano';
$string['showonlyexternal'] = 'Pouze uživatelé z Moodlu';
$string['showonlyexternaldescription'] = 'Nastavím volíte, zda budou v knize známek zobrazení pouze uživatelé z Moodlu(Ano) nebo Všichni dostupní(volba Ne).';

