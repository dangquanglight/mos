<?php	$title = '';	$content = '';	$cms = $data['cms'];	if ($cms){				if ($language->iso == 'en'){			$title = $cms->title_en;			$content = $cms->content_en;		}		else if ($language->iso == 'fi'){			$title = $cms->title_fi;			$content = $cms->content_fi;		}		else if ($language->iso == 'ci'){			$title = $cms->title_ci;			$content = $cms->content_ci;		}	}?><h1 style='margin-bottom:40px'><?php echo $title;?></h1><div class='home-page'><?php echo $content;?></div>