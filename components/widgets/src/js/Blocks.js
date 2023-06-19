import React, { useEffect, useState, useRef, useMemo, memo } from 'react'
import { createPortal } from 'react-dom'
import ProductCat from './ProductCat'
import Filter from './Filter'


const Blocks = memo(({ inStock, inSort, container }) => {
  const products = useRef(JSON.parse(tehnokrat.products).sort((a, b) => {
    const nameA = a.name.toUpperCase(); // ignore upper and lowercase
    const nameB = b.name.toUpperCase(); // ignore upper and lowercase
    if (nameA < nameB) {
      return -1;
    }
    if (nameA > nameB) {
      return 1;
    }
    return 0;
  })).current

  const [stateFilter, setFilter] = useState(
    { catName: products[0].name, })
  const changeFilter = obj => {
    setFilter(obj)
  }

  function absentDown(prod) {
    return prod.sort((a, b) => b.in_stock - a.in_stock)
  }

  //get products for category selected
  let filtered_products = products.filter(e => { return e.name === stateFilter.catName })[0].variations

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
  setFilter({
    ...stateFilter,
    attrbs: titleAttr,
  })

  useEffect(() => {
    let store = []
    if (stateFilter.selected != undefined && stateFilter.selected.length > 0) {
      stateFilter.selected.map(atr => {
        atr.values.map(v => {
          store = store.concat.filtered_products.filter(el => el.title2.includes(v))
        })
        // filtered_products = store
      })
    }

    // if need sort
    if (inSort) {
      store = filtered_products.sort((a, b) => {
        if (inSort === 'upcost') {
          return a.priceUAH - b.priceUAH;
        }
        if (inSort === 'downcost') {
          return b.priceUAH - a.priceUAH;
        }
      })
    }

    const min = Math.min(...store.map(item => item.priceUAH))
    const max = Math.max(...store.map(item => item.priceUAH))
    // setFilter({
    //   ...stateFilter,
    //   min: min,
    //   max: max,
    //   attrbs: titleAttr,
    // })

    filtered_products = absentDown(store)

  }, [stateFilter, inSort])

  return createPortal(
    <div className="all-product">
      <Filter stateFilter={stateFilter} changeFilter={changeFilter} />
      <div className="product-items">{filtered_products.map((product, i) => {
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