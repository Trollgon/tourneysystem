<?php
namespace tourneysystem\form;

use tourneysystem\data\platform\Platform;
use tourneysystem\util\TeamUtil;
use wcf\data\user\User;
use wcf\data\user\UserProfileList;
use wcf\form\AbstractForm;
use wcf\system\exception\IllegalLinkException;
use wcf\system\page\PageLocationManager;
use wcf\system\WCF;
use wcf\util\HeaderUtil;
use wcf\util\StringUtil;
use tourneysystem\data\invitation\InvitationAction;
use tourneysystem\util\TeamInvitationUtil;
use wcf\system\exception\UserInputException;
use wcf\system\request\LinkHandler;
use tourneysystem\data\team\Team;
use wcf\system\exception\PermissionDeniedException;

/**
 * Shows the Form to invite a member to a team.
 *
 * @author	Trollgon
 * @copyright	Trollgon
 * @license	GNU Lesser General Public License <http://www.gnu.org/licenses/lgpl-3.0.txt>
 * @package	de.trollgon.tourneysystem
 */
class TeamInvitationForm extends AbstractForm {
	
	public	$team = '';
	
	public	$platformID = '';
	public 	$teamID = '';
	public 	$playername = '';
	public 	$positionID = '';
	public	$playerID = '';
	public 	$player = '';
	public 	$playerList = null;
	public 	$userOption = '';
	
	public  $formData = array(
			'teamID'		=> '',
			'platformID'	=> '',
			'playerID'		=> '',
			'positionID'	=> '',
	);
	
	/**
	 * @see \wcf\page\AbstractPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		if(isset($_REQUEST['teamID']))
			$this->teamID = intval($_REQUEST['teamID']);
		if($this->teamID == 0) {
			throw new IllegalLinkException();
			}
		$this->team = new Team($this->teamID);
		$this->platformID = $this->team->getPlatformID();
        $platform = new Platform($this->platformID);
        $this->userOption = $platform->getPlatformUserOption();

        $this->playerList = new UserProfileList();
        $this->playerList->setObjectIDs($this->team->getPlayerIDs());
        $this->playerList->readObjects();

        if($this->team->teamID == null || $this->team->teamID == 0) {
            throw new IllegalLinkException();
        }
	}

    /**
     * @see \wcf\page\AbstractPage::readData()
     */
    public function readData() {
        parent::readData();

        PageLocationManager::getInstance()->addParentLocation('de.trollgon.tourneysystem.TeamPage', $this->teamID, $this->team);
        PageLocationManager::getInstance()->addParentLocation("de.trollgon.tourneysystem.TeamList");
        PageLocationManager::getInstance()->addParentLocation("de.trollgon.tourneysystem.TourneyList");
    }
	
	/**
	 * @see \wcf\page\AbstractPage::show()
	 */
	public function show() {
		if (!$this->team->isTeamLeader()) {
				WCF::getSession()->checkPermissions(array("mod.tourneySystem.canEditTeams"));
			}
		else {
			if (TOURNEYSYSTEM_LOCK_TEAMEDIT == true) {
				throw new PermissionDeniedException();
			}
		}
		if (!TeamInvitationUtil::isEmptyPosition($this->teamID, 1) && !TeamInvitationUtil::isEmptyPosition($this->teamID, 2)) {
			throw new PermissionDeniedException();
		}
		parent::show();
	}
	
	/**
	 * @see \wcf\form\AbstractForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		if (isset($_POST['positionID'])) {
			$this->positionID = StringUtil::trim($_POST['positionID']);
			$this->formData['positionID'] = StringUtil::trim($_POST['positionID']);
		}
		if (isset($_POST['playername'])) {
			$this->playername = StringUtil::trim($_POST['playername']);
			$this->player = User::getUserByUsername($this->playername);
		}
		
		$this->formData['platformID'] = $this->platformID;
		$this->formData['teamID'] = $this->teamID;
	}
	
	/**
	 * @see	wcf\form\IForm::validate()
	 */
	public function validate() {
		if ($this->positionID == 0) {
			throw new UserInputException('positionID');
		}
		
		// check if the position is empty
		if (!TeamInvitationUtil::isEmptyPosition($this->teamID, $this->positionID)) {
			throw new UserInputException('positionID', 'notUnique');
		}
		
		if (empty($this->playername)) {
			throw new UserInputException('playername');
		}
		
		//check if the player exists
		if (($this->player->getUserID() == 0)) {
			throw new UserInputException('playername', 'notValid');
		}
		
		$this->playerID = $this->player->getUserID();
		$this->formData['playerID'] = $this->player->getUserID();
				
		// check if the player is not already in the team
		if (!TeamUtil::isNotInTeam($this->teamID, $this->playerID)) {
			throw new UserInputException('playername', 'notUnique');
		}
	}
	
	/**
	 * @see \wcf\form\AbstractForm::save()
	 */
	public function save() {
		parent::save();
		$data = array(
				'data' => array(
						'teamID'		=> $this->formData['teamID'],
						'platformID'	=> $this->formData['platformID'],
						'playerID'		=> $this->formData['playerID'],
						'positionID'	=> $this->formData['positionID'],
				),
		);
		$action = new InvitationAction(array(), 'create', $data);
		$action->executeAction();
		HeaderUtil::delayedRedirect(LinkHandler::getInstance()->getLink('teamInvitation', array(
				'application' 	=> 'tourneysystem',
				'teamID'		=> $this->teamID,
		)),WCF::getLanguage()->get('tourneysystem.team.invitation.successfulRedirect'), 10);
		exit;
	}
	
	/**
	 * @see \wcf\form\AbstractForm::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		WCF::getTPL()->assign(array(
				'team'			=> $this->team,
				'positionID'	=> $this->positionID,
				'formData' 		=> $this->formData,
				'playername' 	=> $this->playername,
				'teamID'		=> $this->teamID,
				'platformID'	=> $this->platformID,
				'contact'		=> $this->team->getContactProfile(),
				'user'			=> $this->team->getContactProfile(),
				'playerList'	=> $this->playerList,
				'userOption'	=> $this->userOption,
                'teamIsEmpty'   => ($this->team->countMembers() < 2),
		));
	}

}