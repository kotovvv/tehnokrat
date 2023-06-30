import { useEffect } from 'react'
import AddToCart from './AddToCart'
import Part from './Part'

const ProductCat = ({ product }) => {
  const variationsAttributesTitles =
    Array.isArray(product.description2)
      ? product.description2.filter((title, index) => (index % 2 === 0))
      : [tehnokrat.strings['color']]
  const attributes = product.title2.substr(1, product.title2.length - 2).split('|')



  return <div className={product.in_stock === 1 ? 'product-item ' : 'product-item not-available'}>
    <div className="product-cont">
      <a href={product.url} class="big-link"></a>
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