<?php
namespace FluidTYPO3\Flux\Tests\Unit\Backend;

/*
 * This file is part of the FluidTYPO3/Flux project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use FluidTYPO3\Flux\Backend\DynamicFlexForm;
use FluidTYPO3\Flux\Tests\Unit\AbstractTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

/**
 * @package Flux
 */
class DynamicFlexFormTest extends AbstractTestCase {

	/**
	 * @test
	 */
	public function canExecuteDataStructurePostProcessHook() {
		$this->canExecuteDataStructurePostProcessHookInternal();
	}

	/**
	 * @test
	 */
	public function canExecuteDataStructurePostProcessHookWithNullFieldName() {
		$this->canExecuteDataStructurePostProcessHookInternal(NULL);
	}

	/**
	 * @test
	 */
	public function canExecuteDataStructurePostProcessHookWithNullFieldAndBadTableName() {
		$this->canExecuteDataStructurePostProcessHookInternal(NULL, 'badtablename');
	}

	/**
	 * @param string $fieldName
	 * @param string $table
	 * @return void
	 */
	protected function canExecuteDataStructurePostProcessHookInternal($fieldName = 'pi_flexform', $table = 'tt_content') {
		$dataStructure = array();
		$config = array();
		$row = array();
		$instance = new DynamicFlexForm();
		$provider1 = $this->getMock('FluidTYPO3\\Flux\\Provider\\Provider', array('postProcessDataStructure'));
		$provider2 = $this->getMock('FluidTYPO3\\Flux\\Provider\\Provider', array('postProcessDataStructure'));
		$provider1->expects($this->once())->method('postProcessDataStructure');
		$provider2->expects($this->once())->method('postProcessDataStructure');
		$providers = array($provider1, $provider2);
		$service = $this->getMock('FluidTYPO3\\Flux\\Service\\FluxService', array('resolveConfigurationProviders'));
		$service->expects($this->once())->method('resolveConfigurationProviders')
			->with($table, $fieldName, $row)->willReturn($providers);
		$instance->injectConfigurationService($service);
		$instance->getFlexFormDS_postProcessDS($dataStructure, $config, $row, $table, $fieldName);
		$isArrayConstraint = new \PHPUnit_Framework_Constraint_IsType(\PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY);
		$this->assertThat($dataStructure, $isArrayConstraint);
	}

}
