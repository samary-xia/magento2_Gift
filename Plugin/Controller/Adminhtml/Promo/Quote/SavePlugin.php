<?php
/**
 * @author:     xiaxixiang
 * @email:      1635055310@qq.com
 * @date:       2020/4/8
 * @descript:   重写购物车规则Save
 */

namespace Samary\Gift\Plugin\Controller\Adminhtml\Promo\Quote;

use Magento\SalesRule\Controller\Adminhtml\Promo\Quote\Save;

class SavePlugin extends Save {

	/**
	 * @var \Samary\Gift\Model\Rules
	 */
	protected $_giftRule;

	/**
	 * SavePlugin constructor.
	 *
	 * @param \Magento\Backend\App\Action\Context $context
	 * @param \Magento\Framework\Registry $coreRegistry
	 * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
	 * @param \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter
	 * @param \Samary\Gift\Model\Rules $rules
	 */
	public function __construct
	(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\Registry $coreRegistry,
		\Magento\Framework\App\Response\Http\FileFactory $fileFactory,
		\Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
		\Samary\Gift\Model\Rules $rules
	) {
		$this->_giftRule = $rules;
		parent::__construct( $context, $coreRegistry, $fileFactory, $dateFilter );
	}

	/**
	 * @param Save $subject
	 * @param \Closure $proceed
	 * @description 修改购物车规则save操作，在设置礼品信息时将礼品一同保存
	 */
	public function aroundExecute( Save $subject, \Closure $proceed ) {
		if ( $this->getRequest()->getPostValue() ) {
			try {
				/** @var $model \Magento\SalesRule\Model\Rule */
				$model = $this->_objectManager->create( \Magento\SalesRule\Model\Rule::class );
				$this->_eventManager->dispatch(
					'adminhtml_controller_salesrule_prepare_save',
					[ 'request' => $this->getRequest() ]
				);
				$data = $this->getRequest()->getPostValue();

				$filterValues = [ 'from_date' => $this->_dateFilter ];
				if ( $this->getRequest()->getParam( 'to_date' ) ) {
					$filterValues['to_date'] = $this->_dateFilter;
				}
				$inputFilter = new \Zend_Filter_Input(
					$filterValues,
					[],
					$data
				);
				$data        = $inputFilter->getUnescaped();
				$id          = $this->getRequest()->getParam( 'rule_id' );
				if ( $id ) {
					$model->load( $id );
					if ( $id != $model->getId() ) {
						throw new \Magento\Framework\Exception\LocalizedException( __( 'The wrong rule is specified.' ) );
					}
				}

				$session = $this->_objectManager->get( \Magento\Backend\Model\Session::class );

				$validateResult = $model->validateData( new \Magento\Framework\DataObject( $data ) );
				if ( $validateResult !== true ) {
					foreach ( $validateResult as $errorMessage ) {
						$this->messageManager->addError( $errorMessage );
					}
					$session->setPageData( $data );
					$this->_redirect( 'sales_rule/*/edit', [ 'id' => $model->getId() ] );

					return;
				}

				if ( isset(
					     $data['simple_action']
				     ) && $data['simple_action'] == 'by_percent' && isset(
					     $data['discount_amount']
				     )
				) {
					$data['discount_amount'] = min( 100, $data['discount_amount'] );
				}
				if ( isset( $data['rule']['conditions'] ) ) {
					$data['conditions'] = $data['rule']['conditions'];
				}
				if ( isset( $data['rule']['actions'] ) ) {
					$data['actions'] = $data['rule']['actions'];
				}
				unset( $data['rule'] );
				$model->loadPost( $data );

				$useAutoGeneration = (int) (
					! empty( $data['use_auto_generation'] ) && $data['use_auto_generation'] !== 'false'
				);
				$model->setUseAutoGeneration( $useAutoGeneration );

				$session->setPageData( $model->getData() );

				$model->save();
				if ( $model->getSimpleAction() == 'by_gift' ) {
					$this->_giftRule->addRules(
						$model->getId(),
						[
							'rule_id'        => $model->getId(),
							'gift_pid'       => $this->getRequest()->getParam( 'gift_pid' ),
							'stop_qty_stack' => $this->getRequest()->getParam( 'stop_qty_stack' )
						]
					);
				}
				$this->messageManager->addSuccess( __( 'You saved the rule.' ) );
				$session->setPageData( false );
				if ( $this->getRequest()->getParam( 'back' ) ) {
					$this->_redirect( 'sales_rule/*/edit', [ 'id' => $model->getId() ] );

					return;
				}
				$this->_redirect( 'sales_rule/*/' );

				return;
			} catch ( \Magento\Framework\Exception\LocalizedException $e ) {
				$this->messageManager->addError( $e->getMessage() );
				$id = (int) $this->getRequest()->getParam( 'rule_id' );
				if ( ! empty( $id ) ) {
					$this->_redirect( 'sales_rule/*/edit', [ 'id' => $id ] );
				} else {
					$this->_redirect( 'sales_rule/*/new' );
				}

				return;
			} catch ( \Exception $e ) {
				$this->messageManager->addError(
					__( 'Something went wrong while saving the rule data. Please review the error log.' )
				);
				$this->_objectManager->get( \Psr\Log\LoggerInterface::class )->critical( $e );
				$this->_objectManager->get( \Magento\Backend\Model\Session::class )->setPageData( $data );
				$this->_redirect( 'sales_rule/*/edit', [ 'id' => $this->getRequest()->getParam( 'rule_id' ) ] );

				return;
			}
		}
		$this->_redirect( 'sales_rule/*/' );
	}
}