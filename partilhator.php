<?php
/*
 *      Partilhator Content Plug-in
 *      @package Partilhator Content Plug-in
 * @subpackage  Content
 *      @author José António Cidre Bardelás
 *      @copyright Copyright (C) 2011-2013 José António Cidre Bardelás and Joomla Empresa. All rights reserved
 *      @license GNU/GPL v3 or later
 *      
 *      Contact us at info@joomlaempresa.com (http://www.joomlaempresa.es)
 *      
 *      This file is part of Partilhator Content Plug-in.
 *      
 *          Partilhator Content Plug-in is free software: you can redistribute it and/or modify
 *          it under the terms of the GNU General Public License as published by
 *          the Free Software Foundation, either version 3 of the License, or
 *          (at your option) any later version.
 *      
 *          Partilhator Content Plug-in is distributed in the hope that it will be useful,
 *          but WITHOUT ANY WARRANTY; without even the implied warranty of
 *          MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *          GNU General Public License for more details.
 *      
 *          You should have received a copy of the GNU General Public License
 *          along with Partilhator Content Plug-in.  If not, see <http://www.gnu.org/licenses/>.
 */
defined('_JEXEC') or die('Acesso restrito');
if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}
jimport('joomla.plugin.plugin');

class plgContentpartilhator extends JPlugin {

	public function __construct(&$subject, $config) {
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	function onContentPrepare($context, &$article, &$params, $limitstart) {
		if (JRequest::getVar('view') == "article" && property_exists($article, 'id') && property_exists($article,'catid')) {
			if (JPluginHelper::isEnabled('content', 'partilhator') == false) 
				return;
			if ($this->params->def('excluir_categorias')) {
				$idSecom = explode(',', str_replace(' ', '', $this->params->def('excluir_categorias')));
				if (in_array($article->catid, $idSecom)) 
					return;
			}
			if ($this->params->def('excluir_artigos')) {
				$idSecom = explode(',', str_replace(' ', '', $this->params->def('excluir_artigos')));
				if (in_array($article->id, $idSecom)) 
					return;
			}
			// $servicos[número] = array(tipo, nome, parâmetro, ligaçom, imagem);
			$servicos[1] = array('shakeit', 'Chuza', 'ver_chuza', 'http://chuza.gl/submit.php?url=', 'jchuza_');
			$servicos[2] = array('shakeit', 'DoMelhor', 'ver_domelhor', 'http://domelhor.net/submit.php?url=', 'jdomelhor_');
			$servicos[3] = array('shakeit', 'La Tafanera', 'ver_latafanera', 'http://latafanera.cat/submit.php?url=', 'jlatafanera_');
			$servicos[4] = array('shakeit', 'Zabaldu', 'ver_zabaldu', 'http://zabaldu.com/submit.php?url=', 'jzabaldu_');
			$servicos[5] = array('shakeit', 'Aupatu', 'ver_aupatu', 'http://aupatu.com/'.$this->params->def('idioma_aupatu', 'eu').'/submit.php?url=', 'jaupatu_');
			$servicos[6] = array('shakeit', 'Meneame', 'ver_meneame', 'http://meneame.net/submit.php?url=', 'jmeneame_');
			$servicos[7] = array('shakeit', 'Tuenti', 'ver_tuenti', 'http://tuenti.com/share?url=', 'jtuenti_');
			$servicos[8] = array('buzzit', 'Buzz', 'ver_buzz', '', 'jbuzz_');
			$servicos[9] = array('shakeit', 'Digg', 'ver_digg', 'http://digg.com/submit?url=', 'jdigg_');
			$servicos[10] = array('shakeit', 'Facebook', 'ver_facebook', 'http://www.facebook.com/share.php?u=', 'jfacebook_');
			$servicos[11] = array('tweettit', 'Twitter', 'ver_twitter', 'http://twitter.com/share?url=', 'jtwitter_');
			$servicos[12] = array('shakeit', 'Cabozo', 'ver_cabozo', 'http://cabozo.com/share.php?url=', 'jcabozo_');
			$servicos[13] = array('inshare', 'In Share', 'ver_inshare', '', '');
			$servicos[14] = array('googleplus', 'Google +1', 'ver_googleplus', '', '');
			$servicos[15] = array('pinit', 'Pin It', 'ver_pinit', '', '');
			$servicos[16] = array('twitter_conta', 'Twitter', 'ver_twitter_conta', '', '');
			$servicos[17] = array('facebook_like', 'Facebook', 'ver_facebook_like', '', '');
			$ladrairo = '';
			$altoBloco = $this->params->def('alto_bloco', 30).'px';
			$espaco_ico = $this->params->def('espaco_icones', 5);
			$tamanho_ico = $this->params->def('tamanho_icones', '24');
			$tamanho_ico_qua = $tamanho_ico.'x'.$tamanho_ico;
			$alinhamento_ico = $this->params->def('alinhamento_icones', 'left');
			// CSS -->
			$margemTopo = $this->params->def('margem_topo', 3).'px';
			$margemPe = $this->params->def('margem_pe', 3).'px';
			$margemEsquerda = $this->params->def('margem_esquerda', 3).'px';
			$margemDireita = $this->params->def('margem_direita', 3).'px';
			$margemSuperiorFacebook = $this->params->def('top_margin_facebook', 5).'px';
			$margemInferiorFacebook = $this->params->def('bottom_margin_facebook', 5).'px';
			$margemSuperiorTwitter = $this->params->def('top_margin_twitter', 2).'px';
			$margemInferiorTwitter = $this->params->def('bottom_margin_twitter', 2).'px';
			$margemSuperiorPinit = $this->params->def('top_margin_pinit', 2).'px';
			$margemInferiorPinit = $this->params->def('bottom_margin_pinit', 2).'px';
			$estilo = '';
			if ($this->params->def('posicom', 'topo') == 'topo' || $this->params->def('posicom', 'topo') == 'pe') {
				$estilo .= <<<REMATE
#partilhator {float: none; display: block; clear: both; width: 100%; height: $altoBloco; margin: $margemTopo $margemDireita $margemPe $margemEsquerda;}
#partilhator.left {text-align: left;}
#partilhator.right {text-align: right;}
#partilhator.center {text-align: center;}
#partilhator img {border: none; margin: 0; padding: 0;}
#partilhator a, #partilhator a:hover, #partilhator a:visited, #partilhator a:link {text-decoration: none; margin: 0; padding: 0; background-color: transparent;}
#partilhator .partilhator_icone {margin-right:${espaco_ico}px; background-color: transparent; display: inline-block}
#partilhator .partilhator_facebook { position: relative; top: $margemSuperiorFacebook; display: inline-block}
#partilhator .partilhator_twitter { position: relative; top: $margemSuperiorTwitter; text-align: left; display: inline-block;}
#partilhator .partilhator_pinit { margin: $margemSuperiorPinit 5px $margemInferiorPinit 5px; text-align: left; display: inline-block;}
REMATE;
			}
			if ($this->params->def('posicom', 'topo') == 'esquerda' || $this->params->def('posicom', 'topo') == 'direita') {
				$estilo .= <<<REMATE
#partilhator_lateral {width: 60px; margin:  $margemTopo $margemDireita $margemPe $margemEsquerda;}
#partilhator_lateral.floatleft {float: left;}
#partilhator_lateral.floatright {float: right;}
#partilhator_lateral img {border: none; margin: 0; padding: 0;}
#partilhator_lateral a, #partilhator_lateral a:hover, #partilhator_lateral a:visited, #partilhator_lateral a:link {text-decoration: none; margin: 0; padding: 0; background-color: transparent;}
#partilhator_lateral .separacom {display: block; height:${espaco_ico}px; background-color: transparent;}
REMATE;
			}
			if ($this->params->def('posicom') == 'topo' || $this->params->def('posicom') == 'pe') {
				if ($this->params->def('ver_googleplus', 0)) 
					$estilo .= "\n#partilhator .partilhator_googleplus {display: inline-block;".($this->params->def('amplo_googleplus') ? " width: ".$this->params->def('amplo_googleplus')."px;" : "").($tamanho_ico == "24" ? "}" : " position: relative; bottom: 1px;}");
				if ($this->params->def('ver_inshare', 0)) 
					$estilo .= "\n#partilhator .partilhator_inshare {display: inline-block; margin-right: ".$espaco_ico."px; ".($tamanho_ico == "24" ? "position: relative; top: 2px;}" : "position: relative; top: 4px;}");
			}
			$doc = &JFactory::getDocument();
			$doc->addStyleDeclaration($estilo);
			// <-- CSS
			if ($this->params->def('posicom') == 'esquerda' || $this->params->def('posicom') == 'direita') {
				$canto = $this->params->def('posicom') == 'esquerda' ? 'left' : 'right';
				$ladrairo .= '<div id="partilhator_lateral" class="float'.$canto.'">';
				if ($this->params->def('ver_chuza', 1)) 
					$ladrairo .= '<a target="_blank" href="http://chuza.gl/submit.php?url='.rawurlencode(utf8_encode($this->plgGetPageUrl($article))).'"><img src="'.JURI::base().'plugins/content/partilhator/partilha_imagens/jchuza.png" height="52" width="52"  alt="Chuza!" /></a><div class="separacom"></div>';
				if ($this->params->def('ver_domelhor', 0)) 
					$ladrairo .= '<a target="_blank" href="http://domelhor.net/submit.php?url='.rawurlencode(utf8_encode($this->plgGetPageUrl($article))).'"><img src="'.JURI::base().'plugins/content/partilhator/partilha_imagens/jdomelhor.png" height="51" width="52"  alt="DoMelhor" /></a><div class="separacom"></div>';
				if ($this->params->def('ver_buzz', 0)) {
					if ($this->params->def('modo_buzz', 0)) {
						$articleURL = htmlspecialchars($this->plgGetPageUrl($article));
						$articleTitle = htmlspecialchars($article->title);
						$pageTitle = htmlspecialchars(JFactory::getConfig()->getValue('config.sitename'));
						$base = JURI::base(false);
						$pageURL = htmlspecialchars($base);
						$buzz = $articleURL.'&title='.$articleTitle.'&srcUrl='.$pageURL.'&srcTitle='.$pageTitle;
						if ($article->introtext) {
							$snippetLimit = (int) $this->params->def('longo_buzz', 1500);
							$buzz .= '&snippet='.$this->getArticleSnippet($article->introtext, $snippetLimit);
						}
						$ladrairo .= '<a style="margin-right: '.$espaco_ico.'px" target="_blank" href="http://www.google.com/reader/link?url='.$buzz.'"><img src="'.JURI::base().'plugins/content/partilhator/partilha_imagens/jbuzz.png" height="62" width="52"  alt="BuzzIt" title="Buzz" /></a><div class="separacom"></div>';
					}
					else {
						$url = str_replace('//', '/', htmlspecialchars($this->plgGetPageUrl($article)));
						$url = str_replace('http:/', 'http://', $url);
						$title = urlencode($article->title);
						$ladrairo .= '<a style="margin-right: '.$espaco_ico.'px" target="_blank" href="http://www.google.com/buzz/post?message='.$title.'&url='.$url.'&imageurl="><img src="'.JURI::base().'plugins/content/partilhator/partilha_imagens/jbuzz.png" height="62" width="52"  alt="BuzzIt" title="Buzz" /></a><div class="separacom"></div>';
					}
				}
				if ($this->params->def('ver_digg', 0)) 
					$ladrairo .= '<a target="_blank" href="http://digg.com/submit?url='.rawurlencode(utf8_encode($this->plgGetPageUrl($article))).'"><img src="'.JURI::base().'plugins/content/partilhator/partilha_imagens/jdigg.png" height="52" width="52"  alt="DiggThis" /></a>';
				$ladrairo .= '</div>';
			}
			else {
				$ladrairo .= '<div id="partilhator" class="'.$alinhamento_ico.'">';
				if ($this->params->def('ordenar_icones', 0)) 
					$ordem = explode(",", $this->params->def('ordem_icones', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17'));
				else 
					$ordem = explode(",", '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17');
				foreach ($ordem as $servico) {
					$ladrairo .= $this->geraCodigoBotoes($servicos, $servico, $this->params, $espaco_ico, $tamanho_ico, $tamanho_ico_qua, $article);
				}
				if (count($ordem) < 17) {
					for ($i = 1; $i < 18; $i++) {
						if (!in_array($i, $ordem)) {
							$ladrairo .= $this->geraCodigoBotoes($servicos, $i, $this->params, $espaco_ico, $tamanho_ico, $tamanho_ico_qua, $article);
						}
					}
				}
				$ladrairo .= '</div>';
			}
			if (($this->params->def('posicom') == 'pe')) 
				$article->text = $article->text.'<!-- Partilhator Plug-in Begin -->'.$ladrairo.'<!-- Partilhator Plug-in End -->';
			else 
				$article->text = '<!-- Partilhator Plug-in Begin -->'.$ladrairo.'<!-- Partilhator Plug-in End -->'.$article->text;
		}
	}

	function plgGetPageUrl(&$obj) {
		if (!is_null($obj)) {
			require_once(JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
			$url = JRoute::_(ContentHelperRoute::getArticleRoute($obj->id, $obj->catid));
			$uri = &JURI::getInstance();
			$base = $uri->toString(array('scheme', 'host', 'port'));
			$url = $base.$url;
			$url = JRoute::_($url, true, 0);
			return $url;
		}
	}

	function getArticleSnippet(&$introText, $snippetLimit) {
		//Ensure that URLs are absolute
		$this->removeLocalURLs($introText, 'src="');
		$this->removeLocalURLs($introText, 'href="');
		$articleSnippet = htmlspecialchars($introText);
		//Trim snippet to avoid Google Reader API error
		if (strlen($articleSnippet) > $snippetLimit) {
			$articleSnippet = substr($articleSnippet, 0, $snippetLimit);
			$introText = htmlspecialchars_decode($articleSnippet);
			$this->cutOpenTag($introText, '<', '>');
			$this->cutOpenTag($introText, '<a', '</a>');
			$articleSnippet = htmlspecialchars($introText);
		}
		return $articleSnippet;
	}

	function removeLocalURLs(&$introText, $source) {
		$sourceLength = strlen($source);
		$sourcePos = strpos($introText, $source, 0);
		while ($sourcePos) {
			if (substr_compare($introText, 'http', $sourcePos + $sourceLength, 4) != 0) {
				$introText = substr_replace($introText, $source.JUri::base(false), $sourcePos, $sourceLength);
			}
			$sourcePos = strpos($introText, $source, $sourcePos + $sourceLength);
		}
	}

	function cutOpenTag(&$introText, $begin, $end) {
		$beginLastPos = strrpos($introText, $begin);
		$endLastPos = strrpos($introText, $end);
		if ($beginLastPos > $endLastPos) {
			$introText = substr($introText, 0, $beginLastPos);
		}
	}

	function geraCodigoBotoes($servicos, $indice, $pluginParams, $espaco_ico, $tamanho_ico, $tamanho_ico_qua, $article) {
		$treito = '';
		switch ($servicos[$indice][0]) {
			case 'shakeit':
				$treito .= $this->shakeit($servicos[$indice][1], $servicos[$indice][2], $servicos[$indice][3], $servicos[$indice][4], $pluginParams, $espaco_ico, $tamanho_ico, $tamanho_ico_qua, $article);
				break;

			case 'tweettit':
				$treito .= $this->tweettit($servicos[$indice][1], $servicos[$indice][2], $servicos[$indice][3], $servicos[$indice][4], $pluginParams, $espaco_ico, $tamanho_ico, $tamanho_ico_qua, $article);
				break;

			case 'buzzit':
				$treito .= $this->buzzit($servicos[$indice][1], $servicos[$indice][2], $servicos[$indice][3], $servicos[$indice][4], $pluginParams, $espaco_ico, $tamanho_ico, $tamanho_ico_qua, $article);
				break;

			case 'googleplus':
				$treito .= $this->googleplus($servicos[$indice][1], $servicos[$indice][2], $servicos[$indice][3], $servicos[$indice][4], $pluginParams, $espaco_ico, $tamanho_ico, $tamanho_ico_qua, $article);
				break;

			case 'inshare':
				$treito .= $this->inshare($servicos[$indice][1], $servicos[$indice][2], $servicos[$indice][3], $servicos[$indice][4], $this->params, $espaco_ico, $tamanho_ico, $tamanho_ico_qua, $article);
				break;
			case 'twitter_conta':
				$treito .= $this->twitter_conta($servicos[$indice][1], $servicos[$indice][2], $servicos[$indice][3], $servicos[$indice][4], $this->params, $espaco_ico, $tamanho_ico, $tamanho_ico_qua, $article);
				break;
			case 'facebook_like':
				$treito .= $this->facebook_like($servicos[$indice][1], $servicos[$indice][2], $servicos[$indice][3], $servicos[$indice][4], $this->params, $espaco_ico, $tamanho_ico, $tamanho_ico_qua, $article);
				break;
			case 'pinit':
				$treito .= $this->pinit($servicos[$indice][1], $servicos[$indice][2], $servicos[$indice][3], $servicos[$indice][4], $espaco_ico, $this->params, $tamanho_ico, $tamanho_ico_qua, $article);
				break;
		}
		return $treito;
	}

	function shakeit($nome, $parametro, $ligacom, $imagem, $pluginParams, $espaco_ico, $tamanho_ico, $tamanho_ico_qua, $article) {
		if ($pluginParams->def($parametro)) 
			return('<div class="partilhator_icone"><a href="'.$ligacom.rawurlencode(utf8_encode($this->plgGetPageUrl($article))).'" target="_blank" ><img style="border: 0;" src="'.JURI::base().'plugins/content/partilhator/partilha_imagens/'.$imagem.$tamanho_ico_qua.'.png" height="'.$tamanho_ico.'" width="'.$tamanho_ico.'"  alt="'.JText::_($nome).'" title="'.JText::_($nome).'" /></a></div>');
		else 
			return('');
	}

	function tweettit($nome, $parametro, $ligacom, $imagem, $pluginParams, $espaco_ico, $tamanho_ico, $tamanho_ico_qua, $article) {
		if ($pluginParams->def($parametro)) {
			//Novo sistema: o twitter reduce o tamanho das ligações e trata bem os diacríticos
			$url = rawurlencode(utf8_encode($this->plgGetPageUrl($article)));
			if (!$pluginParams->def('mensagem_twitter')) 
				$mensagemTwitter = $article->title;
			else 
				$mensagemTwitter = $pluginParams->def('mensagem_twitter');
			$title = urlencode($mensagemTwitter);
			$separator = $pluginParams->def('separator');
			$space = urlencode(' ');
			$tweet = $url;
			return('<div class="partilhator_icone"><a rel="nofollow" href="'.$ligacom.$tweet.'&text='.$pluginParams->def('sitio_curto_twitter', 'Web').': '.$title.'&via='.$pluginParams->def('sitio_longo_twitter', 'Web').'" target="_blank"><img style="border: 0;" src="'.JURI::base().'plugins/content/partilhator/partilha_imagens/'.$imagem.$tamanho_ico_qua.'.png" height="'.$tamanho_ico.'" width="'.$tamanho_ico.'"  alt="'.JText::_($nome).'" title="'.JText::_($nome).'" /></a></div>');
		}
		else 
			return('');
	}

	function buzzit($nome, $parametro, $ligacom, $imagem, $pluginParams, $espaco_ico, $tamanho_ico, $tamanho_ico_qua, $article) {
		if ($pluginParams->def($parametro)) {
			if ($pluginParams->def('modo_buzz', 0)) {
				$articleURL = htmlspecialchars($this->plgGetPageUrl($article));
				$articleTitle = htmlspecialchars($article->title);
				$pageTitle = htmlspecialchars(JFactory::getConfig()->getValue('config.sitename'));
				$base = JUri::base(false);
				$pageURL = htmlspecialchars($base);
				$buzz = $articleURL.'&title='.$articleTitle.'&srcUrl='.$pageURL.'&srcTitle='.$pageTitle;
				if ($article->introtext) {
					$snippetLimit = (int) $pluginParams->def('longo_buzz', 1500);
					$buzz .= '&snippet='.$this->getArticleSnippet($article->introtext, $snippetLimit);
				}
				return('<div class="partilhator_icone"><a  href="http://www.google.com/reader/link?url='.$buzz.'"target="_blank"><img style="border: 0;" src="'.JURI::base().'plugins/content/partilhator/partilha_imagens/'.$imagem.$tamanho_ico_qua.'.png" height="'.$tamanho_ico.'" width="'.$tamanho_ico.'"  alt="'.JText::_($nome).'" title="'.JText::_($nome).'" /></a></div>');
			}
			else {
				$url = str_replace('//', '/', htmlspecialchars($this->plgGetPageUrl($article)));
				$url = str_replace('http:/', 'http://', $url);
				$title = urlencode($article->title);
				return('<div class="partilhator_icone"><a  href="http://www.google.com/buzz/post?message='.$title.'&url='.$url.'&imageurl=" target="_blank"><img style="border: 0;" src="'.JURI::base().'plugins/content/partilhator/partilha_imagens/'.$imagem.$tamanho_ico_qua.'.png" height="'.$tamanho_ico.'" width="'.$tamanho_ico.'"  alt="'.JText::_($nome).'" title="'.JText::_($nome).'" /></a></div>');
			}
		}
		else 
			return('');
	}

	function googleplus($nome, $parametro, $ligacom, $imagem, $pluginParams, $espaco_ico, $tamanho_ico, $tamanho_ico_qua, $article) {
		if ($pluginParams->def($parametro)) {
			$tamanho = ($tamanho_ico == '24') ? 'standard' : 'small';
			$localFinal = '';
			if ($this->params->def('local_googleplus')) {
				$localFinal = $this->params->def('local_googleplus');
			}
			else {
				$local = &JFactory::getLanguage();
				$localCompleto = $local->getLocale();
				$localFinal = explode('.', $localCompleto[0]);
			}
			return('<script src="http://apis.google.com/js/plusone.js" type="text/javascript">'.($localFinal ? '{lang:"'.str_replace('_', '-', $localFinal).'"}' : '').'</script><div class="partilhator_googleplus"><g:plusone size="'.$tamanho.'" href="'.utf8_encode($this->plgGetPageUrl($article)).'"></g:plusone></div>');
		}
		else 
			return('');
	}

	function inshare($nome, $parametro, $ligacom, $imagem, $pluginParams, $espaco_ico, $tamanho_ico, $tamanho_ico_qua, $article) {
		if ($pluginParams->def($parametro)) {
			$doc = &JFactory::getDocument();
			$doc->addScript("http://platform.linkedin.com/in.js");
			$tamanho = ($tamanho_ico == '24') ? 'standard' : 'small';
			return('<div class="partilhator_inshare"><script type="IN/Share" data-url="'.utf8_encode($this->plgGetPageUrl($article)).'" data-counter="right"></script></div>');
		}
		else 
			return('');
	}
	
	function twitter_conta($nome, $parametro, $ligacom, $imagem, $pluginParams, $espaco_ico, $tamanho_ico, $tamanho_ico_qua, $article) {
		if($this->params->def($parametro, 0) == 1) {
			return('<div class="partilhator_twitter"><a href="https://twitter.com/share" class="twitter-share-button" data-url="'.utf8_encode($this->plgGetPageUrl($article)).'" data-counturl="'.utf8_encode($this->plgGetPageUrl($article)).'" data-count="horizontal">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script></div>');
		}
		else 
			return('');
	}

	function facebook_like($nome, $parametro, $ligacom, $imagem, $pluginParams, $espaco_ico, $tamanho_ico, $tamanho_ico_qua, $article) {
		if($this->params->def($parametro, 0) == 1) {
			$doc	= & JFactory::getDocument();
			$config =& JFactory::getConfig();
			if(method_exists($config, 'getValue')) {
			$titulo = $config->getValue('config.sitename').' - '.$doc->getTitle();
			}
			else {
				$titulo = $config->get('config.sitename').' - '.$doc->getTitle();
			}
			$metaOG = "<meta property=\"og:type\" content=\"article\"/>".PHP_EOL;
			$metaOG .= "<meta property=\"og:title\" content=\"".$titulo."\"/>".PHP_EOL;
			$metaOG .= "<meta property=\"og:description\" content=\"".(strip_tags($article->text) != '' ? strip_tags($article->text) : $this->_titulo)."\"/>".PHP_EOL;
			if(method_exists($config, 'getValue')) {
			$metaOG .= "<meta property=\"og:site_name\" content=\"".$config->getValue('sitename')."\"/>".PHP_EOL;
			}
			else {
				$metaOG .= "<meta property=\"og:site_name\" content=\"".$config->get('sitename')."\"/>".PHP_EOL;
			}
			$metaOG .= "<meta property=\"og:url\" content=\"".$this->plgGetPageUrl($article)."\"/>".PHP_EOL;
			
			if(property_exists($article, 'images')) {
				if($img = json_decode($article->images)) {
				  if($img->{'image_intro'} != null) {
					$imagem = JURI::root().$img->{'image_intro'};
				} elseif ($img->{'image_fulltext'} != null) {
					$imagem = JURI::root().$img->{'image_fulltext'};
				  }
				}
			}
			if(!empty($imagem)){
				$metaOG .= "<meta property=\"og:image\" content=\"".$imagem."\"/>".PHP_EOL;
			}
			$doc->addCustomTag($metaOG);
			if($this->params->def('local_facebook')) {
				$localFinal = $this->params->def('local_facebook');
			}
			else {
				$local = &JFactory::getLanguage();
				$localCompleto = $local->getLocale();
				$localSimples = explode('.', $localCompleto[0]);
				$localFinal = $localSimples[0];
			}
			return('<div class="partilhator_facebook"><div id="fb-root"></div>
			<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/'.$localFinal.'/all.js#xfbml=1";
			fjs.parentNode.insertBefore(js, fjs);
			}(document, \'script\', \'facebook-jssdk\'));</script>

			<div class="fb-like" data-href="'.utf8_encode($this->plgGetPageUrl($article)).'" '.($this->params->def('show_send_facebook', 1) ? 'data-send="true" ' : '') .'data-layout="'.$this->params->def('layout_facebook', 'standard').'" data-width="'.$this->params->def('width_facebook', 450).'" data-show-faces="'.($this->params->def('show_faces_facebook', 1) ? 'true' : 'false').'" data-action="'.$this->params->def('verb_facebook', 'like').'" data-colorscheme="'.$this->params->def('color_facebook', 'light').'"></div></div>');
		}
		else 
			return('');
	}
	
	function pinit($nome, $parametro, $ligacom, $imagem, $pluginParams, $espaco_ico, $tamanho_ico, $tamanho_ico_qua, $article) {
		if($this->params->def($parametro, 0) == 1) {
			$doc = &JFactory::getDocument();
			$doc->addScript("//assets.pinterest.com/js/pinit.js");
			$db = JFactory::getDBO();
			if(property_exists($article, 'images')) {
				if($img = json_decode($article->images)) {
				  if($img->{'image_intro'} != null) {
					$imagem = JURI::root().$img->{'image_intro'};
				} elseif ($img->{'image_fulltext'} != null) {
					$imagem = JURI::root().$img->{'image_fulltext'};
				  }
				}
			}
			if(empty($imagem)){
				return('');
			}
			$descricom = strip_tags($article->text) != '' ? strip_tags($article->text) : $this->_titulo;

			return('<div class="partilhator_pinit"><a href="http://pinterest.com/pin/create/button/?url='.utf8_encode($this->plgGetPageUrl($article)).'&media='.$imagem.'&description='.$descricom.'" target="_blank" class="pin-it-button" count-layout="'.$this->params->def('pinit_count', 'horizontal').'"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a></div>');
		}
		else 
			return('');
	}
}
