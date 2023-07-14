import { useEffect } from "react";
import AddToCart from './AddToCart'
import Part from './Part'

const ProductCat = ({ product }) => {
  const variationsAttributesTitles =
    Array.isArray(product.description2)
      ? product.description2.filter((title, index) => (index % 2 === 0))
      : [tehnokrat.strings['color']]
  useEffect(() => {
    const handleProductItemTouchStart = (event) => {
      event.target.classList.add('active');
    };

    const handleProductItemTouchEnd = (event) => {
      event.target.classList.remove('active');
    };

    const handleProductItemMouseEnter = (event) => {
      event.target.classList.add('active');
    };

    const handleProductItemMouseLeave = (event) => {
      event.target.classList.remove('active');
    };

    const productItems = document.querySelectorAll(".product-item");

    productItems.forEach((item) => {
      item.addEventListener('touchstart', handleProductItemTouchStart);
      item.addEventListener('touchend', handleProductItemTouchEnd);
      item.addEventListener('mouseenter', handleProductItemMouseEnter);
      item.addEventListener('mouseleave', handleProductItemMouseLeave);
    });

    return () => {
      productItems.forEach((item) => {
        item.removeEventListener('touchstart', handleProductItemTouchStart);
        item.removeEventListener('touchend', handleProductItemTouchEnd);
        item.removeEventListener('mouseenter', handleProductItemMouseEnter);
        item.removeEventListener('mouseleave', handleProductItemMouseLeave);
      });
    };
  })
  return <div className={product.in_stock === 1 ? 'product-item ' : 'product-item not-available'}>
    <div className="product-cont">
      <a href={product.url} className="big-link"></a>
      {product.label.length > 0 &&
        <span className="pl" style={{ backgroundColor: tehnokrat.label_colors[product.label] }}>{product.label}</span>
      }
      <Part part={product.part} partprivat={product.partprivat} />
      <div className="product-img">
        <img className="prod-image"
          src={product.image} alt={product.title1} />
      </div>
      <a href={product.url}
        className="name">{product.title1}</a>
      <AddToCart productName={product.title1} currentVariation={product} inStock={true} />

      {variationsAttributesTitles.length > 0 ?
        <div className="features">
          <ul>
            {
              variationsAttributesTitles.map((el, idx) => {
                return <li key={idx}>
                  <span>{el}: </span>{product.description2[idx * 2 + 1]}
                </li>
              })
            }
          </ul>
        </div>
        : ''}

    </div>
  </div >
}

export default ProductCat