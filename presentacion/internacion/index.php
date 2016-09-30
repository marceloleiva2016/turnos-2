<?php
/*Agregado para que tenga el usuario*/
include_once '../../namespacesAdress.php';
include_once negocio.'usuario.class.php';
include_once datos.'sectorDatabaseLinker.class.php';
include_once datos.'internacionDatabaseLinker.class.php';

$dbInt = new InternacionDatabaseLinker();

$int = $dbInt->crearInternacion(2, "Tac con perdida de conocimiento, se refiere a internacion", 58, 2, 0);

var_dump($int);

session_start();

if(!isset($_SESSION['usuario']))
{
    //echo "WHOOPSS, No se encontro ningun usuario registrado";
    header("Location: ../index.php?logout=1");
}

$usuario = $_SESSION['usuario'];

$data = unserialize($usuario);

$array = array();

$array[1]['nombre'] = " Javier Ignacio Molina Cano" ;
$array[1]['cama'] = "1001" ;

$array[2]['nombre'] = " Lillian Eugenia Gómez Álvarez" ;
$array[2]['cama'] = "1002 " ;

$array[3]['nombre'] = " Sixto Naranjo Marín" ;
$array[3]['cama'] = "1003 " ;

$array[4]['nombre'] = " Gerardo Emilio Duque Gutiérrez" ;
$array[4]['cama'] = "1004 " ;

$array[5]['nombre'] = " Jhony Alberto Sáenz Hurtado" ;
$array[5]['cama'] = "1005 " ;

$array[6]['nombre'] = " Germán Antonio Lotero Upegui" ;
$array[6]['cama'] = "1006 " ;

$array[7]['nombre'] = " Oscar Darío Murillo Gonzále" ;
$array[7]['cama'] = "1007 " ;

$array[8]['nombre'] = " Augusto Osorno Gil" ;
$array[8]['cama'] = "1008 " ;

$array[9]['nombre'] = " César Oswaldo Palacio Martíne" ;
$array[9]['cama'] = "1009 " ;

$array[10]['nombre'] = " Gloria Amparo Alzate Agudelo" ;
$array[10]['cama'] = "10010" ;

$array[11]['nombre'] = " Héctor Iván González Castaño" ;
$array[11]['cama'] = "10011" ;

$array[12]['nombre'] = " Beatriz Elena Osorio Laverde" ;
$array[12]['cama'] = "10012" ;

$array[13]['nombre'] = " Herman Correa Ramírez" ;
$array[13]['cama'] = "10013" ;

$array[14]['nombre'] = " Carlos Mario Montoya Serna" ;
$array[14]['cama'] = "10014" ;

$array[15]['nombre'] = " Carlos Augusto Giraldo" ;
$array[15]['cama'] = "10015" ;

$array[16]['nombre'] = " Arturo Tabares Mora" ;
$array[16]['cama'] = "10016" ;

$array[17]['nombre'] = " William de J Ramírez Vásquez" ;
$array[17]['cama'] = "10017" ;

$array[18]['nombre'] = " Jaime Lopez Tobón" ;
$array[18]['cama'] = "10018" ;

$array[19]['nombre'] = " Gloria Elena Sanclemente Zea" ;
$array[19]['cama'] = "10019" ;

$array[20]['nombre'] = " Carlos Alberto Villegas Loper" ;
$array[20]['cama'] = "10020" ;

$array[21]['nombre'] = " Jorge Uribe Boter" ;
$array[21]['cama'] = "10021" ;

$array[22]['nombre'] = " Maria Isabel López Gaviria" ;
$array[22]['cama'] = "10022" ;

$array[23]['nombre'] = " Alfredo Tobón Tobón" ;
$array[23]['cama'] = "10023" ;

$array[24]['nombre'] = " Héctor Damián Mosquera Benítez" ;
$array[24]['cama'] = "10024" ;

$array[25]['nombre'] = " Álvaro Iván Berdugo López" ;
$array[25]['cama'] = "10025" ;

$array[26]['nombre'] = " Carlos Alberto Zárate Yépez" ;
$array[26]['cama'] = "10026" ;

$array[27]['nombre'] = " Hernán Darío Hurtado Pérez" ;
$array[27]['cama'] = "10027" ;

$array[28]['nombre'] = " Jorge León Ruiz Ruiz" ;
$array[28]['cama'] = "10028" ;

$array[29]['nombre'] = " John Jairo Duque García" ;
$array[29]['cama'] = "10029" ;

$array[30]['nombre'] = "Vacia" ;
$array[30]['cama'] = "10030" ;

$array[31]['nombre'] = " Elkin Octavio Díaz Pérez" ;
$array[31]['cama'] = "10031" ;

$array[32]['nombre'] = " Julio Cesar Rodas Monsalve " ;
$array[32]['cama'] = "10032" ;

$array[33]['nombre'] = " Gabriel Jaime Jiménez Gómez" ;
$array[33]['cama'] = "10033" ;

$array[34]['nombre'] = " José Didier Zapata Suárez" ;
$array[34]['cama'] = "10034" ;

$array[35]['nombre'] = " Bernardo Posada Vera" ;
$array[35]['cama'] = "10035" ;

$array[36]['nombre'] = " Luis Guillermo Vélez Osorio" ;
$array[36]['cama'] = "10036" ;

$array[37]['nombre'] = " Horacio Augusto Moreno Correa" ;
$array[37]['cama'] = "10037" ;

$array[38]['nombre'] = " Alejandro Alzate Garcés" ;
$array[38]['cama'] = "10038" ;

$array[39]['nombre'] = " Javier Ignacio Aguilar Gómez" ;
$array[39]['cama'] = "10039" ;

$array[40]['nombre'] = " Adolfo León Correa Silva" ;
$array[40]['cama'] = "10040" ;

$array[41]['nombre'] = " Gustavo Hernán Rodríguez Vallejo" ;
$array[41]['cama'] = "10041" ;

$array[42]['nombre'] = " Oscar Darío Gómez Giraldo" ;
$array[42]['cama'] = "10042" ;

$array[43]['nombre'] = " Gonzalo López Gaviria" ;
$array[43]['cama'] = "10043" ;

$array[44]['nombre'] = " Héctor Manuel Pineda" ;
$array[44]['cama'] = "10044" ;

$array[45]['nombre'] = " Maria Victoria Arias Gómez" ;
$array[45]['cama'] = "10045" ;

$array[46]['nombre'] = " Luis Alfonso Escobar Trujillo " ;
$array[46]['cama'] = "10046" ;

$array[47]['nombre'] = " Lida Patricia Giraldo Morales" ;
$array[47]['cama'] = "10047" ;

$array[48]['nombre'] = " Luis Oliverio Cárdenas Moreno" ;
$array[48]['cama'] = "10048" ;

$array[49]['nombre'] = " Luis Fernando Castro Hernández" ;
$array[49]['cama'] = "10049" ;

$array[50]['nombre'] = " Julio Cesar Rodríguez Monsalve" ;
$array[50]['cama'] = "10050" ;

$array[51]['nombre'] = " Álvaro de Jesús Saldarriaga Vásquez" ;
$array[51]['cama'] = "10051" ;

$array[52]['nombre'] = " Luis Aníbal Sepúlveda Villada" ;
$array[52]['cama'] = "10052" ;

$array[53]['nombre'] = " Beatriz Elena Puerta Bolívar" ;
$array[53]['cama'] = "10053" ;

$array[54]['nombre'] = " Ángel Gabriel Arrubla Ortiz" ;
$array[54]['cama'] = "10054" ;

$array[55]['nombre'] = " Álvaro de Jesús Bocanumenth Puerta" ;
$array[55]['cama'] = "10055" ;

$array[56]['nombre'] = " Fabio Alexander Florez García" ;
$array[56]['cama'] = "10056" ;

$array[57]['nombre'] = " Héctor Darío Bermúdez Saldarriaga" ;
$array[57]['cama'] = "10057";

$dbSector = new SectorDatabaseLinker();

$sectores = $dbSector->getSectores();

?>
<!DOCTYPE>
<htmls>
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Internacion</title>
		<meta name="description" content="A responsive, magazine-like website layout with a grid item animation effect when opening the content" />
		<meta name="keywords" content="grid, layout, effect, animated, responsive, magazine, template, web design" />
		<meta name="author" content="Codrops" />
		<link rel="shortcut icon" href="../favicon.ico">
		<link media="screen" type="text/css" rel="stylesheet" href="../includes/css/barra.css">
		<link media="screen" type="text/css" rel="stylesheet" href="../includes/css/iconos.css">
		<link rel="stylesheet" type="text/css" href="includes/css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="includes/fonts/font-awesome-4.3.0/css/font-awesome.min.css" />
		<link rel="stylesheet" type="text/css" href="includes/css/style1.css" />
		<script src="includes/js/modernizr.custom.js"></script>
	</head>
	<body>
		<!-- barra -->
		<div id="barra" >
			<!-- navegar -->
	 		<div id="barraImage" >
	 			<span style="font-size: 2em;" class="icon icon-about"></span>
	        </div>
	        <div id="navegar">
	        	&nbsp;&nbsp;&nbsp;<a href="../menu/">Sistema SITU</a>&nbsp;&gt;&nbsp;<a href="#">Internacion</a>
	        </div>
	        <!-- /navegar-->
	        <!-- usuario -->
            <div id="usuario">
                <a href="../usuario/"><span class="icon icon-boy"> </span>Usuario | <?=$data->getNombre()?></a>
            </div>
            <!-- /usuario-->
		</div>
		<!-- /barra -->
		<div class="container">
			<button id="menu-toggle" class="menu-toggle"><span>Menu</span></button>
			<div id="theSidebar" class="sidebar">
				<button class="close-button fa fa-fw fa-close"></button>
				<h1><span>Listado<span>Internacion</h1>
				<div class="related">
					<h3>Salas</h3>
					<?php
					for ($z=0; $z < count($sectores); $z++) { 
						echo "<a href='#'>".$sectores[$z]->getDetalle()."</a>";
					}
					?>
				</div>
			</div>
			<div id="theGrid" class="main">
				<section class="grid">
					<header class="top-bar">
						<img class="meta_logo" src="includes/img/authors/bed_logo_blue.png" /><h2 class="top-bar__headline">Internados en Clinica Medica Hombres</h2>
					</header>
					<?php
					for ($i=1; $i < count($array); $i++) {
					?>

					<a class="grid__item" href="#">
						<h2 class="title title--preview"><?php echo $array[$i]['cama']?></h2>
						<div class="loader"></div>
						<span class="category"><?php echo $array[$i]['nombre']?></span><br>
					</a>

					<?php
					}
					?>
					<footer class="page-meta">
						<span>Cantidad Internados: 53</span>&nbsp;
						<span>Camas libres: 17</span>
					</footer>
				</section>
				<section class="content">
					<div class="scroll-wrap">
						<?php
						for ($i=1; $i < count($array); $i++) {
						?>
						<article class="content__item">
							<span class="category category--full">Nro <?php echo $array[$i]['cama']?></span>
							<h2 class="title title--full"><?php echo $array[$i]['nombre']?></h2>
							<div class="meta meta--full">
								<img class="meta__avatar" src="includes/img/authors/1.png" alt="author01" />
								<span class="meta__author">Internado <?php echo $array[$i]['nombre']?></span>
								<span class="meta__date"><i class="fa fa-calendar-o"></i> <?php echo $i?> Apr</span>
								<span class="meta__reading-time"><i class="fa fa-clock-o"></i> 3 min read</span>
							</div>
							<p>I am fully aware of the shortcomings in these essays. I shall not touch upon those which are characteristic of first efforts at investigation. The others, however, demand a word of explanation.</p>
							<p>The four essays which are here collected will be of interest to a wide circle of educated people, but they can only be thoroughly understood and judged by those who are really acquainted with psychoanalysis as such. It is hoped that they may serve as a bond between students of ethnology, philology, folklore and of the allied sciences, and psychoanalysts; they cannot, however, supply both groups the entire requisites for such co-operation. They will not furnish the former with sufficient insight into the new psychological technique, nor will the psychoanalysts acquire through them an adequate command over the material to be elaborated. Both groups will have to content themselves with whatever attention they can stimulate here and there and with the hope that frequent meetings between them will not remain unproductive for science.</p>
							<p>The two principal themes, totem and taboo, which give the name to this small book are not treated alike here. The problem of taboo is presented more exhaustively, and the effort to solve it is approached with perfect confidence. The investigation of totemism may be modestly expressed as: “This is all that psychoanalytic study can contribute at present to the elucidation of the problem of totemism.” This difference in the treatment of the two subjects is due to the fact that taboo still exists in our midst. To be sure, it is negatively conceived and directed to different contents, but according to its psychological nature, it is still nothing else than Kant’s ‘Categorical Imperative’, which tends to act compulsively and rejects all conscious motivations. On the other hand, totemism is a religio-social institution which is alien to our present feelings; it has long been abandoned and replaced by new forms. In the religions, morals, and customs of the civilized races of to-day it has left only slight traces, and even among those races where it is still retained, it has had to undergo great changes. The social and material progress of the history of mankind could obviously change taboo much less than totemism.</p>
							<p>If I judge my readers’ impressions correctly, I dare say that after hearing all that was said about taboo they are far from knowing what to understand by it and where to store it in their minds. This is surely due to the insufficient information I have given and to the omission of all discussions concerning the relation of taboo to superstition, to belief in the soul, and to religion. On the other hand I fear that a more detailed description of what is known about taboo would be still more confusing; I can therefore assure the reader that the state of affairs is really far from clear. We may say, however, that we deal with a series of restrictions which these primitive races impose upon themselves; this and that is forbidden without any apparent reason; nor does it occur to them to question this matter, for they subject themselves to these restrictions as a matter of course and are convinced that any transgression will be punished automatically in the most severe manner. There are reliable reports that innocent transgressions of such prohibitions have actually been punished automatically. For instance, the innocent offender who had eaten from a forbidden animal became deeply depressed, expected his death and then actually died. The prohibitions mostly concern matters which are capable of enjoyment such as freedom of movement and unrestrained intercourse; in some cases they appear very ingenious, evidently representing abstinences and renunciations; in other cases their content is quite incomprehensible, they seem to concern themselves with trifles and give the impression of ceremonials. Something like a theory seems to underlie all these prohibitions, it seems as if these prohibitions are necessary because some persons and objects possess a dangerous power which is transmitted by contact with the object so charged, almost like a contagion. The quantity of this dangerous property is also taken into consideration. Some persons or things have more of it than others and the danger is precisely in accordance with the charge. The most peculiar part of it is that any one who has violated such a prohibition assumes the nature of the forbidden object as if he had absorbed the whole dangerous charge. This power is inherent in all persons who are more or less prominent, such as kings, priests and the newly born, in all exceptional physical states such as menstruation, puberty and birth, in everything sinister like illness and death and in everything connected with these conditions by virtue of contagion or dissemination.</p>
							<p>First of all it must be said that it is useless to question savages as to the real motivation of their prohibitions or as to the genesis of taboo. According to our assumption they must be incapable of telling us anything about it since this motivation is ‘unconscious’ to them. But following the model of the compulsive prohibition we shall construct the history of taboo as follows: Taboos are very ancient prohibitions which at one time were forced upon a generation of primitive people from without, that is, they probably were forcibly impressed upon them by an earlier generation. These prohibitions concerned actions for which there existed a strong desire. The prohibitions maintained themselves from generation to generation, perhaps only as the result of a tradition set up by paternal and social authority. But in later generations they have perhaps already become ‘organized’ as a piece of inherited psychic property. Whether there are such ‘innate ideas’ or whether these have brought about the fixation of the taboo by themselves or by co-operating with education no one could decide in the particular case in question. The persistence of taboo teaches, however, one thing, namely, that the original pleasure to do the forbidden still continues among taboo races. They therefore assume an _ambivalent attitude_ toward their taboo prohibitions; in their unconscious they would like nothing better than to transgress them but they are also afraid to do it; they are afraid just because they would like to transgress, and the fear is stronger than the pleasure. But in every individual of the race the desire for it is unconscious, just as in the neurotic.</p> <p>It seems like an obvious contradiction that persons of such perfection of power should themselves require the greatest care to guard them against threatening dangers, but this is not the only contradiction revealed in the treatment of royal persons on the part of savages. These races consider it necessary to watch over their kings to see that they use their powers in the right way; they are by no means sure of their good intentions or of their conscientiousness. A strain of mistrust is mingled with the motivation of the taboo rules for the king. “The idea that early kingdoms are despotisms”, says Frazer[59], “in which the people exist only for the sovereign, is wholly inapplicable to the monarchies we are considering. On the contrary, the sovereign in them exists only for his subjects: his life is only valuable so long as he discharges the duties of his position by ordering the course of nature for his people’s benefit. So soon as he fails to do so, the care, the devotion, the religious homage which they had hitherto lavished on him cease and are changed into hatred and contempt; he is ignominiously dismissed and may be thankful if he escapes with his life. Worshipped as a god one day, he is killed as a criminal the next. But in this changed behaviour of the people there is nothing capricious or inconsistent. On the contrary, their conduct is quite consistent. If their king is their god he is or should be, also their preserver; and if he will not preserve them he must make room for another who will. So long, however, as he answers their expectations, there is no limit to the care which they take of him, and which they compel him to take of himself. A king of this sort lives hedged in by ceremonious etiquette, a network of prohibitions and observances, of which the intention is not to contribute to his dignity, much less to his comfort, but to restrain him from conduct which, by disturbing the harmony of nature, might involve himself, his people, and the universe in one common catastrophe. Far from adding to his comfort, these observances, by trammelling his every act, annihilate his freedom and often render the very life, which it is their object to preserve, a burden and sorrow to him.”</p>
							<p>by Sigmund Freud.</p>
						</article>
						<?php
						}
						?>
					</div>
					<button class="close-button"><i class="fa fa-close"></i><span>Close</span></button>
				</section>
			</div>
		</div><!-- /container -->
		<script src="includes/js/classie.js"></script>
		<script src="includes/js/main.js"></script>
	</body>
</html>
