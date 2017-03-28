<?php
/*
 *      Partilhator Content Plug-in
 *      @package Partilhator Content Plug-in
 *      @subpackage Content
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
	class JFormFieldTitle extends JFormField {
		protected $type = 'title';
		public function getInput() {
			return '<div style="clear: both; text-align: center; padding: 3px; background: -moz-linear-gradient(center top , #639CEE 5%, #AFC9EE 100%) repeat scroll 0 0 #639CEE; background-image: -webkit-gradient(linear, 0 0%, 0 100%, color-stop(0.05, #639CEE), to(#AFC9EE)); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#639CEE\',endColorstr=\'#AFC9EE\'); border: 1px solid #344C6E; border-radius: 5px; box-shadow: 0 1px 0 0 #80ACEB inset; -moz-box-shadow: inset 0px 1px 0px 0px #80ACEB; -webkit-box-shadow: inset 0px 1px 0px 0px #80ACEB; color: #204272; display: block; font-family: \'Ubuntu\',sans-serif; font-weight: bold; text-shadow: 1px 1px 0 #C5D6ED; -moz-border-radius: 5px; -webkit-border-radius: 5px">'.JText::_($this->value).'</div>';
		}
	}
