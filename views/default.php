<div class="container">
	<h1><?=$data['result']['name']?></h1>
	<?=$data['result']['text']?>
	
	<?
	if(!empty($data['result']['items'])){?>
	<table class="table">
	<?
	foreach($data['result']['items'] as $row){?>
		<tr>
		<?
		foreach($row as $column){?>
		<td>
			<?=$column?>
		</td>
		<?
		}?>
		</tr>
	<?
	}?>
	</table>
	<?
	}?>
</div>