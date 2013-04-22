<?php /* Smarty version 2.6.17, created on 2013-04-22 20:30:19
         compiled from Test/test.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'url', 'Test/test.html', 4, false),)), $this); ?>
<h1><?php echo $this->_tpl_vars['title']; ?>
</h1>
<p><?php echo $this->_tpl_vars['content']; ?>
</p>
<p><?php echo $this->_tpl_vars['foo']; ?>
</p>
<?php echo template_url_encode(array('_c' => 'test','_a' => 'test','foo' => 'bar','user' => 'jimmy'), $this);?>

Done!
