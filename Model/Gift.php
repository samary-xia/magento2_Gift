<?php
/**
 * @author:     xiaxixiang
 * @email:      1635055310@qq.com
 * @date:       2020/3/27
 * @descript:   将产品加入购物车
 */

namespace Samary\Gift\Model;

use Magento\{
	Framework\App\Helper\Context,
	Setup\Exception,
	Framework\Data\Form\FormKey,
	Checkout\Model\CartFactory,
	Catalog\Model\ProductFactory
};

class Gift extends \Magento\Framework\App\Helper\AbstractHelper {

	/**
	 * @var FormKey
	 */
	protected $_formKey;

	/**
	 * @var
	 */
	protected $_cart;

	/**
	 * @var ProductFactory
	 */
	protected $_product;

	/**
	 * Gift constructor.
	 *
	 * @param Context $context
	 * @param FormKey $formKey
	 * @param CartFactory $cart
	 * @param ProductFactory $product
	 */
	public function __construct
	(
		Context $context,
		FormKey $formKey,
		CartFactory $cart,
		ProductFactory $product
	) {
		$this->_formKey = $formKey;
		$this->_cart    = $cart->create();
		$this->_product = $product;
		parent::__construct( $context );
	}

	/**
	 * @param $giftPid
	 * @param $qty
	 *
	 * @return bool
	 * @description 增加礼品
	 */
	public function adds( $gifts ) {
		foreach ( $gifts as $giftPid => $qty ) {
			$params[] = [
				'form_key' => $this->_formKey->getFormKey(),
				'product'  => $giftPid, //product Id
				'qty'      => $qty //quantity of product
			];
		}

		foreach ( $params as $param ) {
			$this->_add( $param );
		}


		return true;
	}

	/**
	 * @param $param
	 *
	 * @return bool
	 */
	protected function _add( $param ) {
		$_product = $this->_product->create()->load( $param['product'] );
		$this->_cart->addProduct( $_product, $param );

		return true;

	}
}