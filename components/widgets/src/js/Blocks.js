import React, { useEffect, useState, useRef, useMemo, memo } from 'react'
import { createPortal } from 'react-dom'
import ProductCat from './ProductCat'
import Filter from './Filter'




function absentDown(prod) {
  return prod.sort((a, b) => b.in_stock - a.in_stock)
}

const Blocks = memo(({ inStock, inSort, container }) => {


  const [stateFilProd, setFilProd] = useState([])
  //get all products and sort name
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

  //state for filter
  const [stateFilter, setFilter] = useState(
    //store first category products
    { catName: products[0].name, selected: [] })
  const changeFilter = obj => {
    setFilter(obj)
  }

  //state for filter
  const [stateAtrVal, setAtrVal] = useState([])

  //get products for store category selected
  const getCatProducts = () => { return products.filter(e => { return e.name === stateFilter.catName })[0].variations }

  //array names atributes for products
  const variationsAttributesTitles = () => {
    return Array.isArray(stateFilProd[0].description2)
      ? stateFilProd[0].description2.filter((title, index) => (index % 2 === 0))
      : [tehnokrat.strings['color']]
  }

  const getAtrVal = () => {
    let titleAttr = []

    for (let i = 0; i < stateFilProd[0].attributes2.length; i++) {
      let attrs = []
      stateFilProd.map(variation => {
        if (!attrs.includes(variation.attributes2[i])) {
          attrs.push(variation.attributes2[i])
        }
      })
      if (attrs.length) {
        titleAttr.push({ name: variationsAttributesTitles()[i], attrs: attrs })
      }
    }
    setAtrVal(titleAttr)
  }

  useEffect(() => {
    setFilProd(getCatProducts())
    console.log('33333333333333')
    console.log(stateFilProd)

    // getAtrVal()
  }, [])

  useEffect(() => {
    setFilProd(getCatProducts())
    // getAtrVal()
    //if need filter products
    if (stateFilter.selected != undefined && stateFilter.selected.length > 0) {
      let store = []
      stateFilter.selected.map(atr => {
        atr.values.map(v => {
          store = store.concat.stateFilProd.filter(el => el.title2.includes(v))
        })
        setFilProd(store)
      })
    }
    // get min max price selected products
    const min = Math.min(...stateFilProd.map(item => item.priceUAH))
    const max = Math.max(...stateFilProd.map(item => item.priceUAH))

    // setFilter({
    //   ...stateFilter,
    //   min: min,
    //   max: max,
    //   attrbs: titleAttr,
    // })

    //outstock products bottom of list
    setFilProd(absentDown(stateFilProd))

  }, [stateFilter])


  useEffect(() => {
    if (inSort) {
      setFilProd(stateFilProd.sort((a, b) => {
        if (inSort === 'upcost') {
          return a.priceUAH - b.priceUAH;
        }
        if (inSort === 'downcost') {
          return b.priceUAH - a.priceUAH;
        }
      }))
    }
  }, [inSort])

  return createPortal(
    <div className="all-product">
      <Filter stateFilter={stateFilter} changeFilter={changeFilter} stateAtrVal={stateAtrVal} products={products} />
      <div className="product-items">{stateFilProd.map(product => {
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