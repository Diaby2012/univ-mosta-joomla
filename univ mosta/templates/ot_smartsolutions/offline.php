<?php
/**
*	@version	$Id: offline.php 9 2013-03-21 09:47:13Z linhnt $
*	@package	OMG Responsive Template for Joomla! 2.5
*	@subpackage	template ot_smartsolutions
*	@copyright	Copyright (C) 2009 - 2013 Omegatheme. All rights reserved.
*	@license	GNU/GPL version 2, or later
*	@website:	http://www.omegatheme.com
*	Support Forum - http://www.omegatheme.com/forum/
*/

defined('_JEXEC') or die;
$app = JFactory::getApplication();
// JHtml::_('bootstrap.framework');
// JHtmlBootstrap::loadCss($includeMaincss = true, $this->direction);
use Jarvis\Template\Builder;
$builder = Builder::instance();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta name="HandheldFriendly" content="true" />
	<meta name="apple-mobile-web-app-capable" content="YES" />
	<title><?php echo htmlspecialchars($app->getCfg('sitename')) . ' - ' . $this->title; ?></title>
	<?php $builder->head() ?>
	<?php
	$doc = JFactory::getDocument();
	// Add Stylesheets
	$doc->addStyleSheet('templates/'.$this->template.'/assets/css/font-awesome.min.css');
	$doc->addStyleSheet('templates/'.$this->template.'/assets/css/bootstrap.min.css');
	$doc->addStyleSheet('templates/'.$this->template.'/assets/css/bootstrap-extended.css');
	$doc->addStyleSheet('templates/'.$this->template.'/assets/css/template.css');
	$doc->addStyleSheet('templates/'.$this->template.'/assets/css/preset-style-1.css');
	// Add JavaScripts
	$doc->addScript('templates/' .$this->template. '/assets/js/bootstrap.min.js');
	$doc->addScript('templates/' .$this->template. '/assets/js/otscript.js');
	// Add Fonts
	$doc->addStyleSheet('http://fonts.googleapis.com/css?family=Oswald|Open+Sans|Open+Sans+Condensed');
	?>

	<jdoc:include type="head" />
</head>
<body id="ot-body" class="<?php $builder->bodyClasses() ?> ot-offline">
	<div class="wrapper">
		<div class="section" id="oTopBlock">
			<div class="container">
				<div class="row">
					<?php if ($this->countModules( 'logo' )) { ?>
						<?php if ($this->countModules( 'navigator' )) { ?>
							<div class="col-lg-3 col-lg-reset col-md-3 col-md-reset col-sm-3 col-sm-reset col-xs-12 col-xs-reset" id="ot-logo">
								<jdoc:include type="modules" name="logo" style="standard" />
							</div>
							<div class="col-lg-9 col-lg-reset col-md-9 col-md-reset col-sm-9 col-sm-reset col-xs-12 col-xs-reset" id="ot-top-area">
								<jdoc:include type="modules" name="navigator" style="standard" />
							</div>
						<?php } else { ?>
							<div class="col-lg-12 col-lg-reset col-md-12 col-md-reset col-sm-12 col-sm-reset col-xs-12 col-xs-reset" id="ot-logo">
								<jdoc:include type="modules" name="logo" style="standard" />
							</div>
						<?php } ?>
					<?php } else { ?>
						<h1 class="text-center">
							<?php echo htmlspecialchars($app->getCfg('sitename')); ?>
						</h1>
					<?php } ?>
				</div>
			</div>
		</div>
		<jdoc:include type="message" />
		<div class="section" id="offline">
			<div class="container">
				<div class="row">
					<div class="text-center">
						<div class="offline_message">
							<?php if ($app->getCfg('offline_image')) : ?>
							<img src="<?php echo $app->getCfg('offline_image'); ?>" alt="<?php echo htmlspecialchars($app->getCfg('sitename')); ?>" />
							<?php endif; ?>
							
							<?php if ($app->getCfg('display_offline_message', 1) == 1 && str_replace(' ', '', $app->getCfg('offline_message')) != '') : ?>
								<p>
									<?php echo $app->getCfg('offline_message'); ?>
								</p>
							<?php elseif ($app->getCfg('display_offline_message', 1) == 2 && str_replace(' ', '', JText::_('JOFFLINE_MESSAGE')) != '') : ?>
								<p>
									<?php echo JText::_('JOFFLINE_MESSAGE'); ?>
								</p>
							<?php endif; ?>
						</div>
						<div class="clearfix"></div>
						<!-- <div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
							<form action="<?php echo JRoute::_('index.php', true); ?>" method="post" id="form-login" class="form-horizontal">
								<fieldset class="input">
									<div id="form-login-username" class="control-group form-group">
										<div class="controls">
											<div class="input-group">
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-user" title="<?php echo JText::_('JGLOBAL_USERNAME') ?>"></span>
													<label for="username" class="element-invisible"><?php echo JText::_('JGLOBAL_USERNAME'); ?></label>
												</span>
												<input id="username" type="text" name="username" class="input form-control" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_USERNAME') ?>" />
											</div>
										</div>
									</div>
									<div id="form-login-password" class="control-group form-group">
										<div class="controls">
											<div class="input-prepend input-append input-group">
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-lock" title="<?php echo JText::_('JGLOBAL_PASSWORD') ?>">
													</span>
														<label for="passwd" class="element-invisible"><?php echo JText::_('JGLOBAL_PASSWORD'); ?>
													</label>
												</span>
												<input id="passwd" type="password" name="password" class="input form-control" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />
											</div>
										</div>
									</div>
									<div id="form-login-remember" class="control-group form-group">
										<div class="checkbox text-left">
											<label for="remember" class="control-label"><input id="remember" type="checkbox" name="remember" class="inputbox" value="yes"/> <?php echo JText::_('JGLOBAL_REMEMBER_ME') ?></label> 
										</div>
										</div>
									<div id="form-login-submit" class="control-group form-group">
										<div class="controls">
											<button type="submit" tabindex="0" name="Submit" class="btn btn-primary btn"><?php echo JText::_('JLOGIN') ?></button>
										</div>
									</div>
									<input type="hidden" name="option" value="com_users" />
									<input type="hidden" name="task" value="user.login" />
									<input type="hidden" name="return" value="<?php echo base64_encode(JURI::base()) ?>" />
									<?php echo JHtml::_('form.token'); ?>
								</fieldset>
							</form>
						</div>
						<div class="clearfix"></div> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
