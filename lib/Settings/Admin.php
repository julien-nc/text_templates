<?php
namespace OCA\TextTemplates\Settings;

use OCA\TextTemplates\Db\TemplateMapper;
use OCA\TextTemplates\Service\TemplateService;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Services\IInitialState;
use OCP\IConfig;
use OCP\Settings\ISettings;

use OCA\TextTemplates\AppInfo\Application;

class Admin implements ISettings {

	private IConfig $config;
	private IInitialState $initialStateService;
	private ?string $userId;
	private TemplateService $templateService;
	private TemplateMapper $templateMapper;

	public function __construct(IConfig       $config,
								TemplateService $templateService,
								TemplateMapper $templateMapper,
								IInitialState $initialStateService,
								?string       $userId) {
		$this->config = $config;
		$this->initialStateService = $initialStateService;
		$this->userId = $userId;
		$this->templateService = $templateService;
		$this->templateMapper = $templateMapper;
	}

	/**
	 * @return TemplateResponse
	 */
	public function getForm(): TemplateResponse {
		$templates = $this->templateMapper->getTemplatesOfUser(null);

		$state = [
			'templates' => $templates,
		];
		$this->initialStateService->provideInitialState('admin-config', $state);
		return new TemplateResponse(Application::APP_ID, 'adminSettings');
	}

	public function getSection(): string {
		return 'server';
	}

	public function getPriority(): int {
		return 90;
	}
}
