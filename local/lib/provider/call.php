<?php

namespace Sw\Infinity;




//namespace Bitrix\Crm\Activity\Provider;
/*
use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity;
*/
use Bitrix\Main\Localization\Loc;
use Bitrix\Crm\Settings\ActivitySettings;

Loc::loadMessages(__FILE__);

class CallInfinity extends \Bitrix\Crm\Activity\Provider\Call 
{
	//const ACTIVITY_PROVIDER_ID = 'INFINITY_CALL';VOXIMPLANT_CALL
	const ACTIVITY_PROVIDER_ID = 'VOXIMPLANT_CALL';
	const ACTIVITY_PROVIDER_TYPE_CALL = 'CALL';
	const ACTIVITY_PROVIDER_TYPE_CALLBACK = 'CALLBACK';

	/**
	 * @param array $activity Activity data.
	 * @return string Title.
	
	public static function getPlannerTitle(array $activity)
	{
		return Loc::getMessage('VOXIMPLANT_ACTIVITY_PROVIDER_CALL_PLANNER_ACTION_NAME');
	}
	*/

	public static function displayTime($duration)
	{
		
		$secs = intval($duration*60*60*24);
		
		$hours = intval($secs / 3600);
		$minutes = intval((($secs / 60) % 60));
		$seconds = $secs % 60;
		
		if($seconds < 10) $seconds = "0".$seconds;
		
		if($hours > 0)
		{
			if($minutes < 10) $minutes = "0".$minutes;			
		}
		
		return ($hours > 0?"$hours:":"").$minutes.':'.$seconds;
	}
	
	/**
	 * @inheritdoc
	 */
	public static function renderView(array $activity)
	{
		global $APPLICATION;
		
		
		{	
			//echo "<pre>"; var_dump($activity,1); echo "</pre>";
			
			$call_info = "";			// /infinity/ajax/ajax_get_call_file.php
			
			if(!empty($activity['PROVIDER_PARAMS']))
			{
				$params = $activity['PROVIDER_PARAMS'];
				if($params['finished'] == '1')
				{					
					
					if(!empty($params['connects']))
						foreach($params['connects'] as $conn){	
					
							$link = "/infinity/ajax/ajax_get_call_file.php?connectid=".$conn['ID'];
							
							$call_info .= "<audio src='$link&nocahe=6' controls preload='none' typeoff='audio/vnd.wave' style='vertical-align: middle;padding-right: 30px;' ></audio> ";
							$call_info .= "<a href='$link&source=true' target=_blank style='vertical-align: middle;'>[Скачать запись звонка, wav]</a><br>";
						}
										
					$call_info .= "<br><span style='opacity: 0.8; font-size: 125%;'>Длительность разговора <strong>".self::displayTime($params['DurationTalk'])."</strong> (ожидание <strong>".self::displayTime($params['DurationWait'])."</strong>). </span> ";
					
				}else{
					$call_info .= "<span style='color: #a2a2a2 /*#20b756*/; /*font-weight: bold;*/ font-size: 125%;'>Звонок не завершён...</span>";
				}
				
				$call_info = "<div style='background: rgba(59, 200, 245, 0.18);    padding: 8px;    border-radius: 7px;    margin-bottom: 20px;'>$call_info</div>";
				
				
				/*
				$link = "/infinity/ajax/ajax_get_call_file.php?connectid=0"; //"http://192.168.200.204:10080/stat/getrecordedfile/?IDConnection=5007182329";
			
				$call_info .= "<audio src='$link' controls preload='metadata' typeoff='audio/vnd.wave' style='vertical-align: middle;padding-right: 30px;' ></audio> ";
				$call_info .= "<a href='$link' target=_blank style='vertical-align: middle;'>[Скачать запись звонка]</a><br>";
				
				*/
				
				
				/*				
				ob_start();
				$APPLICATION->IncludeComponent(
					"bitrix:player",
					"",
					Array(
						"PLAYER_TYPE" => "flv",
						"PROVIDER" => "video",
						"CHECK_FILE" => "N",
						"USE_PLAYLIST" => "N",
						"PATH" => $link,
						"WIDTH" => 250,
						"HEIGHT" => 24,
						"PREVIEW" => false,
						"LOGO" => false,
						"FULLSCREEN" => "N",
						"SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
						"SKIN" => "",
						"CONTROLBAR" => "bottom",
						"WMODE" => "transparent",
						"WMODE_WMV" => "windowless",
						"HIDE_MENU" => "N",
						"SHOW_CONTROLS" => "Y",
						"SHOW_STOP" => "Y",
						"SHOW_DIGITS" => "Y",
						"CONTROLS_BGCOLOR" => "FFFFFF",
						"CONTROLS_COLOR" => "000000",
						"CONTROLS_OVER_COLOR" => "000000",
						"SCREEN_COLOR" => "000000",
						"AUTOSTART" => "N",
						"REPEAT" => "N",
						"VOLUME" => "90",
						"DISPLAY_CLICK" => "play",
						"MUTE" => "N",
						"HIGH_QUALITY" => "N",
						"ADVANCED_MODE_SETTINGS" => "Y",
						"BUFFER_LENGTH" => "10",
						"DOWNLOAD_LINK" => false,
						"DOWNLOAD_LINK_TARGET" => "_self",
						"ALLOW_SWF" => "N",
						"ADDITIONAL_PARAMS" => array(
							'LOGO' => false,
							'NUM' => false,
							'HEIGHT_CORRECT' => false,
						),
						"PLAYER_ID" => "bitrix_inf_record_".rand(0,5000)
					),
					false,
					Array("HIDE_ICONS" => "Y")
				);
				$call_info .= ob_get_clean();
				*/
			}
			
			
			return '<div class="crm-task-list-call">
				<div class="crm-task-list-call-info">				
					<div>'.$call_info.'</div>
					<div class="crm-task-list-call-info-container">
						<span class="crm-task-list-call-info-name">
							'.Loc::getMessage('VOXIMPLANT_ACTIVITY_PROVIDER_CALL_DESCRIPTION').':
						</span>
					</div>
					<span>
						'.$activity['DESCRIPTION_HTML'].'
					</span>
				</div>
			</div>';
		}

		ob_start();
		$APPLICATION->IncludeComponent(
			'bitrix:crm.activity.call',
			'',
			array(
				'ACTIVITY' => $activity,
				'CALL_ID' => (strpos($activity['ORIGIN_ID'], 'VI_') === false ? null : substr($activity['ORIGIN_ID'], 3)),
			)
		);
		return ob_get_clean();
	}

}