import React, { useEffect, useState, useRef, useMemo, memo } from 'react'
import { createPortal } from 'react-dom'
import ProductCat from './ProductCat'
import Filter from './Filter'

function absentDown(prod) {
  return prod.sort((a, b) => b.in_stock - a.in_stock);
}

const Blocks = memo(({ inStock, inSort, container }) => {
  // get all products and sort name
  const products = useRef(
    JSON.parse(tehnokrat.products).sort((a, b) => {
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
  ).current;

  //state for filter
  const [stateFilter, setFilter] = useState(
    //store first category products
    { catName: products[0].name, selected: [] }
  );
  const [isLoading, setLoading] = useState(true);

  const changeFilter = (obj) => {
    setFilter(obj);
  };

  //get products for store category selected
  let filtered_products = products.filter((e) => {
    return e.name === stateFilter.catName;
  })[0].variations;

  //atributes for products
  const variationsAttributesTitles = Array.isArray(
    filtered_products[0].description2
  )
    ? filtered_products[0].description2.filter(
      (title, index) => index % 2 === 0
    )
    : [tehnokrat.strings["color"]];

  let titleAttr = [];
  for (let i = 0; i < filtered_products[0].attributes2.length; i++) {
    let attrs = [];
    filtered_products.map((variation) => {
      if (!attrs.includes(variation.attributes2[i])) {
        attrs.push(variation.attributes2[i]);
      }
    });
    if (attrs.length) {
      titleAttr.push({ name: variationsAttributesTitles[i], attrs: attrs });
    }
  }

  //if need filter products
  if (stateFilter.selected !== undefined && stateFilter.selected.length > 0) {
    let store = [];
    stateFilter.selected.forEach((atr) => {
      atr.values.forEach((v) => {
        const items = filtered_products.filter((el) =>
          el.attributes2.includes(v)
        );
        store = [...store, ...items];
      });

      let newStore = Array.from(new Set(store));

      if (atr.name === "Ємність накопичувача") {
        newStore = newStore.filter((item) =>
          atr.values.includes(item.attributes2[1])
        );
      }

      filtered_products = newStore;
      console.log("filtered_products", filtered_products);
    });
  }

  useEffect(() => {
    // if need sort products
    if (inSort) {
      filtered_products = filtered_products.sort((a, b) => {
        if (inSort === "upcost") {
          return a.priceUAH - b.priceUAH;
        }
        if (inSort === "downcost") {
          return b.priceUAH - a.priceUAH;
        }
      });
    }

    // get min max price selected products
    const min = Math.min(...filtered_products.map((item) => item.priceUAH));
    const max = Math.max(...filtered_products.map((item) => item.priceUAH));

    setFilter({
      ...stateFilter,
      min: min,
      max: max,
      attrbs: titleAttr,
    });
    setLoading(false);

    //outstock products bottom of list
    filtered_products = absentDown(filtered_products);
  }, [inSort]);

  useEffect(() => {

    const proditem_height = jQuery(".product-item .product-cont").height();
    jQuery('.product-item').css('min-height', proditem_height + 40);

    jQuery(".product-item").on({
      touchstart: function () {
        jQuery(this).addClass('active');
      },
      touchend: function () {
        jQuery(this).removeClass('active');
      }
    });

    jQuery(".product-item").on({
      mouseenter: function () {
        jQuery(this).addClass('active');
      },
      mouseleave: function () {
        jQuery(this).removeClass('active');
      }
    });

    const pi = document.getElementsByClassName("product-item");

    let j;
    for (j = 0; j < pi.length; j++) {
      pi[j].addEventListener("mouseover", function () {
        this.classList.toggle("active");
        let pichild = this.querySelector('.features');
        if (pichild.style.maxHeight) {
          pichild.style.maxHeight = null;
        } else {
          pichild.style.maxHeight = pichild.scrollHeight + "px";
        }
      });
    }

  }, [filtered_products]);

  return createPortal(
    <>
      {!isLoading && (
        <div className="all-product">
          <Filter stateFilter={stateFilter} changeFilter={changeFilter} />
          <div className="product-items">
            {filtered_products.map((product) => {
              return (
                <ProductCat
                  key={product.id}
                  product={product}
                  inStock={inStock}
                />
              );
            })}
          </div>
        </div>
      )}
    </>,
    container
  );
});

export default Blocks;
