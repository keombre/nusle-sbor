<?php
header('Content-Type: text/html; charset=utf-8');
// gather directory info -> link, name DONE!
// display it in neet fasion DONE
// maybe add upload option NOT NEEDED
// maybe make it work with kázání as well

$ch = curl_init("https://drive.google.com/embeddedfolderview?id=0Bxtw0L4_LE5GSzNEeGFLWU1TVmM#list");
curl_setopt_array($ch, array(CURLOPT_HEADER => 0, CURLOPT_RETURNTRANSFER => 1));
$page = curl_exec($ch);
curl_close($ch);

//links (?<=info"><a href=").*?(?=")
preg_match_all('/(?<=info"><a href=").*?(?=")/m', $page, $links);

//names (?<=flip-entry-title">).*?(?=<\/div)
preg_match_all('/(?<=flip-entry-title">).*?(?=<\/div)/m', $page, $names);

$files = array("errs" => array());
//062016
//$names = array(array("0615", "0515", "0415", "a0216", "b0216", "0316"));

foreach ($names[0] as $id => $name_ext) {
	$name_ext_ex = explode('.', $name_ext);
	$name = $name_ext_ex[count($name_ext_ex)-2];
	preg_match_all('/[0-9]/', substr($name, -4), $numbers);
	if (count($numbers[0]) != 4) {
		array_push($files['errs'], $name." is a wrong name<br />");
	} else {
		$month = intval(substr($name, -4, 2));
		$year = intval(substr($name, -2));
		if (!array_key_exists($year, $files)) {
			$files[$year] = array();
		}
		if (array_key_exists($month, $files[$year])) {
			array_push($files['errs'], $name." is conflicting with ".$files[$year][$month]['name']);
		} else {
			$files[$year][$month] = array("name" => $name, "link" => $links[0][$id]);
		}
	}
}
/*nice JS
var files = JSON.parse('<?php echo (json_encode($files));?>');
		var link = "";
		
		function showMonth() {
			var elm = document.getElementById('month');
			if (window.getComputedStyle(elm).getPropertyValue('display') == "none") {
				elm.removeAttribute('style');
			} else {
				elm.innerHTML = "<option disabled selected>Měsíc</option>";
				document.getElementById('submit').style.display = "none";
			}
			var year = parseInt(document.getElementById('year').value);
			Object.keys(files[year]).forEach(function(month) {
				console.log(month);
				elm.innerHTML += '<option value="'+files[year][month]['link']+'">'+translate(month)+'</option>';
			});
		}
		
		function showSubmit() {
			var elm = document.getElementById('submit');
			if (window.getComputedStyle(elm).getPropertyValue('display') == "none") {
				elm.removeAttribute('style');
			}
			link = document.getElementById('month').value;
		}
		
		function download() {
			var parts = link.split("/");
			window.open("https://docs.google.com/uc?authuser=0&id="+parts[5]+"&export=download", "_blank");
		}
		
		function translate(number) {
			switch (number) {
				case "1": return "Leden";
				case "2": return "Únor";
				case "3": return "Březen";
				case "4": return "Duben";
				case "5": return "Květen";
				case "6": return "Červen";
				case "7": return "Červenec"; Čec + Srp
				case "8": return "Srpen";
				case "9": return "Září";
				case "10": return "Říjen";
				case "11": return "Listopad";
				case "12": return "Prosinec";
			}
		}
		
		window.onload=function() {
			document.getElementById('down').onclick=download;
			Object.keys(files).forEach(function(year) {
				if (year != "errs") {
					document.getElementById('year').innerHTML += '<option value="'+year+'">20'+year+'</option>';
				}
			});
		}
*/
?>
<!DOCTYPE html>
<html>
	<head>
		<script>
			var files=JSON.parse('<?php echo (json_encode($files));?>'),link="";
			function showMonth(){var a=document.getElementById("month");"none"==window.getComputedStyle(a).getPropertyValue("display")?a.removeAttribute("style"):(a.innerHTML="<option disabled selected>M\u011bs\u00edc</option>",document.getElementById("submit").style.display="none");var c=parseInt(document.getElementById("year").value);Object.keys(files[c]).forEach(function(b){a.innerHTML+='<option value="'+files[c][b].link+'">'+translate(b)+"</option>"})}
			function showSubmit(){var a=document.getElementById("submit");"none"==window.getComputedStyle(a).getPropertyValue("display")&&a.removeAttribute("style");link=document.getElementById("month").value}function download(){var a=link.split("/");window.open("https://docs.google.com/uc?authuser=0&id="+a[5]+"&export=download","_blank")}
			function translate(a){switch(a){case "1":return"Leden";case "2":return"\u00danor";case "3":return"B\u0159ezen";case "4":return"Duben";case "5":return"Kv\u011bten";case "6":return"\u010cerven";case "7":return"\u010Crc + Srp";case "8":return"\u010Crc + Srp";case "9":return"Z\u00e1\u0159\u00ed";case "10":return"\u0158\u00edjen";case "11":return"Listopad";case "12":return"Prosinec"}}
			window.onload=function(){document.getElementById("down").onclick=download;Object.keys(files).forEach(function(a){"errs"!=a&&(document.getElementById("year").innerHTML+='<option value="'+a+'">20'+a+"</option>")})};
		</script>
		<style>
			select{width:85px;height:38px;line-height:32px;color:#7C7C7C;padding:5px 10px;background-color:#fdfdfb;border:1px solid #eaeae8;box-shadow:0 0 4px 1px #e3e3e3 inset;outline:none;font-size:100%;margin:0;vertical-align:baseline;font-size:100%;margin:0;vertical-align:baseline;margin-left:10px}option{line-height:32px}body{margin:0;height:100%;font-family:'PT Sans Narrow',sans-serif;margin-left:100px}button{color:#fff!important;background:url(form_button.jpg) no-repeat scroll right -37px #257185;border-left:1px solid #257185;height:37px;padding:0 40px 0 20px;overflow-y:hidden;border:none;box-shadow:none;text-shadow:1px 1px 1px #555;text-decoration:none;cursor:pointer;-webkit-appearance:button;line-height:normal;font-size:100%;margin:0;vertical-align:baseline}#month{width:120px}a{margin-left:12px;font-size:15px;text-decoration:none;font-weight:400;text-align:center;text-shadow:none;position:relative;top:12px;color:#00f}a:hover{text-decoration:underline}a:visited{color:#00f}
		</style>
	</head>
	<body>
		<select id='year' onchange="showMonth();">
		  <option disabled selected>Rok</option>
		</select>
		<select id='month' style='display: none;' onchange="showSubmit();">
			<option disabled selected>Měsíc</option>
		</select>
		<span id='submit' style='display: none;'>
			<button id='show' onclick='window.open(link, "_blank");'>Zobrazit</button>
			<a href='#' id='down'>Pouze stáhnout</a>
		</span>
	</body>
</html>