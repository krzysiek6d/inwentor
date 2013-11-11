<?php
/**
    autor: Krzysztof Pawluch
 */

$cakeDescription = __d('cake_dev', 'Inwentor! ');
?>
<!DOCTYPE html>

<html>
<head>
        
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php /*echo $cakeDescription*/ ?>:
		<?php echo 'Inwentor :';/*$title_for_layout;*/ ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
                echo $this->Html->script('jquery-1.9.0.min');
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1><?php 
                            echo $this->Html->link($cakeDescription,  array('controller' => 'pages', 'action' => 'display', 'home'));
                            
                            if($isLogged) 
                            {
                                echo "&nbsp&nbsp&nbsp&nbsp::";
                                echo $this->Html->link('Produkty', array('controller' => 'items', 'action' => 'index', 'clear' => true));
                                echo "&nbsp&nbsp&nbsp&nbsp::";
                                echo $this->Html->link('Klienci', array('controller' => 'clients', 'action' => 'index'));
                                
                                echo '<span style="float:right">';
                                echo "  ::";
                                echo $this->Html->link('Wyloguj', array('controller' => 'users', 'action' => 'logout')); 
                                echo '</span>';
                            }
                            else 
                            {
                                echo '<span style="float:right">';
                                echo "  ::";
                                echo $this->Html->link('Zaloguj', array('controller' => 'users', 'action' => 'login'));
                                echo '</span>';
                            }
                        ?>
                        </h1>
                       
		</div>
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
			<?php /*echo $this->Html->link(
					$this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')),
					'http://www.cakephp.org/',
					array('target' => '_blank', 'escape' => false)
				);
			*/?>
		</div>
	</div>
	<?php 
            echo $this->element('sql_dump'); 
        ?>
</body>
</html>
