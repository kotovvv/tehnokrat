import React, { useRef, useState, memo, useEffect, useCallback } from "react";
import { createPortal } from 'react-dom'
import ProductCat from './ProductCat'
import Filter from './Filter'
import Pagination from "./components/common/Pagination";

import { paginate, range } from "./utils";

function absentDown(prod) {
  return prod.sort((a, b) => b.in_stock - a.in_stock);
}

const Blocks = memo(({ inStock, inSort, container, inDisplay }) => {
  // get all products and sort name
  const prods = useRef(
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

  // Transform in_stock prop in productsList
  function transformData(data) {
    const newProds = [];
    data.forEach(item => {
      const variations = item.variations.map((el, i) => {
        if (i % 2 === 0) {
          el.in_stock = 0;
          return el;
        }
        return el;
      })
      newProds.push({ name: item.name, variations });
    })

    return newProds;
  }
  const products = transformData(prods);
  // --- end

  //state for filter
  const [stateFilter, setFilter] = useState(
    //store first category products
    { catName: products[0].name, selected: [] }
  );
  const [isLoading, setLoading] = useState(true);
  const [currentPage, setCurrentPage] = useState(1);
  // const [titleAttr, setAttr] = useState();
  const pageSize = 9;

  const changeFilter = (obj) => {
    setFilter(obj);
    setCurrentPage(1);
  };

  //get products for store category selected
  let filtered_products = products.filter((e) => {
    return e.name === stateFilter.catName;
  })[0].variations;

  //atributes for products
  const variationsAttributesTitles = Array.isArray(filtered_products[0].description2)
    ? filtered_products[0].description2.filter((title, index) => index % 2 === 0)
    : [tehnokrat.strings["color"]];

  let titleAttr = [];
  for (let i = 0; i < filtered_products[0].attributes2.length; i++) {
    let attrs = [];
    let colors = []
    filtered_products.map((variation) => {
      if (!attrs.includes(variation.attributes2[i])) {
        attrs.push(variation.attributes2[i]);
      }
      if (variation.attributes2[i].startsWith("#")) {
        let colorName = variation.title2.split("|")[0].slice(1)
        if (!colors.find((el) => { el[colorName] })) {
          colors.push({ [variation.attributes2[i]]: colorName });
        }
      }

    });
    if (attrs.length) {
      titleAttr.push({ name: variationsAttributesTitles[i], attrs: attrs, colors: colors });
    }
  }


  // Ranged by progress bar
  let ranged_products = filtered_products
  // range(filtered_products, {
  //   minPrice: stateFilter?.setMin,
  //   maxPrice: stateFilter?.setMax,
  // });

  //if need filter products
  if (stateFilter.selected !== undefined && stateFilter.selected.length > 0) {
    let store = [];
    stateFilter.selected.forEach((atr) => {
      atr.values.forEach((v) => {
        const items = ranged_products.filter((el) =>
          el.attributes2.includes(v)
        );
        store = [...store, ...items];
      });

      let newStore = Array.from(new Set(store));

      const capacityItem = stateFilter.attrbs[1].name;

      if (atr.name === capacityItem) {
        newStore = newStore.filter((item) =>
          atr.values.includes(item.attributes2[1])
        );
      }

      ranged_products = newStore;
    });
  }
  useEffect(() => {
    jQuery('.all-product .filter .filter-open').on('click', function () {
      jQuery('.all-product .filter .filter-cont').addClass('active');
    });

    jQuery('.all-product .filter .close').on('click', function () {
      jQuery('.all-product .filter .filter-cont').removeClass('active');
    });

    // jcf.replace(jQuery('.filter-item .checkbox-item input[type=checkbox]'));

    // const proditem_height = jQuery(".product-item .product-cont").height();
    // jQuery('.product-item').css('height', proditem_height + 40);

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
  })

  useEffect(() => {
    jcf.destroyAll('.range-input')
    // if need sort products
    //   if (inSort) {
    //     ranged_products = ranged_products.sort((a, b) => {
    //       if (inSort === "upcost") {
    //         return a.priceUAH - b.priceUAH;
    //       } else {
    //         return b.priceUAH - a.priceUAH;
    //       }
    //     });
    //   }

    // get min max price selected products

    function getMinPrice(array) {
      return Math.min(...array.map((item) => item.priceUAH));
    }

    function getMaxPrice(array) {
      return Math.max(...array.map((item) => item.priceUAH));
    }

    const min = ranged_products.length
      ? getMinPrice(ranged_products)
      : getMinPrice(filtered_products);
    const max = ranged_products.length
      ? getMaxPrice(ranged_products)
      : getMaxPrice(filtered_products);

    setFilter({
      ...stateFilter,
      min: min,
      max: max,
      attrbs: titleAttr,
    });
    setLoading(false);

    //   //outstock products bottom of list
    //   ranged_products = absentDown(ranged_products);
  }, [stateFilter.catName]);

  useEffect(() => {
    setCurrentPage(1);
  }, [stateFilter?.setMin, stateFilter?.setMax, stateFilter.selected]);

  if (inSort) {
    ranged_products = ranged_products.sort((a, b) => {
      if (inSort === "upcost") {
        return a.priceUAH - b.priceUAH;
      } else {
        return b.priceUAH - a.priceUAH;
      }
    });
  }
  //outstock products bottom of list
  ranged_products = absentDown(ranged_products);

  const count = ranged_products.length;
  console.log(ranged_products)
  const productsCrop = paginate(ranged_products, currentPage, pageSize);

  const handleChangePage = (pageIndex) => {
    setCurrentPage(pageIndex);
  };

  const handlePageChangeByArrows = (number) => {
    setCurrentPage((prevState) => prevState + number);
  };

  return createPortal(
    <>
      {!isLoading && (
        <div className="all-product">
          <Filter stateFilter={stateFilter} changeFilter={changeFilter} inDisplay={inDisplay} />
          <div className="product-items">
            {productsCrop.map((product) => {
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
      <Pagination
        itemsCount={count}
        pageSize={pageSize}
        currentPage={currentPage}
        onChangePage={handleChangePage}
        onChangePageByArrows={handlePageChangeByArrows}
      />
    </>,
    container
  );
});

export default Blocks;
