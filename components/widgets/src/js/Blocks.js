import React, { useEffect, useState, useRef, useMemo, memo } from 'react'
import { createPortal } from 'react-dom'
import ProductCat from './ProductCat'
import Filter from './Filter'


//get all products and sort name
const products = JSON.parse(tehnokrat.products).sort((a, b) => {
  const nameA = a.name.toUpperCase(); // ignore upper and lowercase
  const nameB = b.name.toUpperCase(); // ignore upper and lowercase
  if (nameA < nameB) {
    return -1;
  }
  if (nameA > nameB) {
    return 1;
  }
  return 0;
})

function absentDown(prod) {
  return prod.sort((a, b) => b.in_stock - a.in_stock)
}

const Blocks = memo(({ inStock, inSort, container }) => {

  //state for filter
  const [stateFilter, setFilter] = useState(
    //store first category products
    { catName: products[0].name })
  const changeFilter = obj => {
    setFilter(obj)
  }

  //get products for store category selected
  let filtered_products = products.filter(e => { return e.name === stateFilter.catName })[0].variations

  //atributes for products
  const variationsAttributesTitles = Array.isArray(filtered_products[0].description2)
    ? filtered_products[0].description2.filter((title, index) => (index % 2 === 0))
    : [tehnokrat.strings['color']]
  let titleAttr = []
  for (let i = 0; i < filtered_products[0].attributes2.length; i++) {
    let attrs = []
    filtered_products.map(variation => {
      if (!attrs.includes(variation.attributes2[i])) {
        attrs.push(variation.attributes2[i])
      }
    })
    if (attrs.length) {
      titleAttr.push({ name: variationsAttributesTitles[i], attrs: attrs })
    }
  }

  // set filter min max price selected products


  useEffect(() => {
    //if need filter products
    // if (stateFilter.selected != undefined && stateFilter.selected.length > 0) {
    //   let store = []
    //   stateFilter.selected.map(atr => {
    //     atr.values.map(v => {
    //       store = store.concat.filtered_products.filter(el => el.title2.includes(v))
    //     })
    //     filtered_products = store
    //   })
    // }

    // if need sort products
    if (inSort) {
      filtered_products = filtered_products.sort((a, b) => {
        if (inSort === 'upcost') {
          return a.priceUAH - b.priceUAH;
        }
        if (inSort === 'downcost') {
          return b.priceUAH - a.priceUAH;
        }
      })
    }

    // get min max price selected products
    // const min = Math.min(...filtered_products.map(item => item.priceUAH))
    // const max = Math.max(...filtered_products.map(item => item.priceUAH))

    setFilter({
      ...stateFilter,
      min: min,
      max: max,
      attrbs: titleAttr,
    })

    //outstock products bottom of list
    filtered_products = absentDown(filtered_products)

  }, [inSort, filtered_products])

  return createPortal(
    <div className="all-product">
      <Filter stateFilter={stateFilter} changeFilter={changeFilter} />
      <div className="product-items">{filtered_products.map(product => {
        return <ProductCat key={product.id}
          product={product} inStock={inStock}
        />
      })}
      </div>
    </div>
    ,
    container
  )
})

export default Blocks