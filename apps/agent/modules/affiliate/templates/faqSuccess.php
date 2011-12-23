<h1>Zapna Global &#8211; FAQ</h1>


<?php foreach ($Faqs as $faqs): ?>
  <?php echo '<b><font size=2>Question:</font></b> '.$faqs->getQuestion().'<br /><br />' ?>
  <?php echo '<p style=nowrap><b><font size=2>Answer:</font></b>&nbsp;'.$faqs->getAnswer();
  echo '</p><br />';?>
<?php endforeach; ?>
