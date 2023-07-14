import React, { useRef, useState, memo, useEffect } from "react";
import { createPortal } from 'react-dom'
import ProductCat from './ProductCat'
import Filter from './Filter'
import Pagination from "./components/common/Pagination";

import { paginate } from "./utils";

function absentDown(prod) {
  return prod.sort((a, b) => b.in_stock - a.in_stock);
}

const Blocks = memo(({ inSort, container }) => {
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

  const [stateFilter, setFilter] = useState({
    catName: products[0].name,
    selected: [],
    min: 0,
    max: 0,
    attrbs: []
  });
  const [isLoading, setLoading] = useState(true);
  const [currentPage, setCurrentPage] = useState(1);
  const pageSize = 9;

  const changeFilter = (obj) => {
    setFilter(prevState => ({
      ...prevState,
      ...obj,
      selected: obj.selected,
      min: obj.min,
      max: obj.max,
      attrbs: obj.attrbs
    }));
    setCurrentPage(1);
  };

  let filtered_products = [];
  let titleAttr = [];
  if (stateFilter.catName === '') {
    products.forEach((cat) => {
      cat.variations.forEach(el => {
        filtered_products.push(el)
      });
    });
  } else {
    filtered_products = products.find((e) => e.name === stateFilter.catName)?.variations || [];

    const variationsAttributesTitles = Array.isArray(filtered_products[0]?.description2)
      ? filtered_products[0].description2.filter((title, index) => index % 2 === 0)
      : [tehnokrat.strings["color"]];

    const prepareAttributeForSort = attribute => {
      attribute = tehnokrat.product_attributes.reduce((attribute, attributeData) => {
        return attribute.replace(attributeData.search, attributeData.replace)
      }, attribute.toString())

      return attribute.replace(/[^0-9]/g, '')
    }

    for (let i = 0; i < filtered_products[0]?.attributes2?.length; i++) {
      let attrs = [];
      let colors = [];
      filtered_products.forEach((variation) => {
        if (!attrs.includes(variation.attributes2[i])) {
          attrs.push(variation.attributes2[i]);
        }
        if (variation.attributes2[i].startsWith("#")) {
          let colorName;
          if (variation.title2.includes('|')) {
            colorName = variation.title2.split("|")[0].slice(1);
          } else {
            colorName = variation.title2.slice(1, -1);
          }
          if (!colors.find((el) => el[colorName])) {
            colors.push({ [variation.attributes2[i]]: colorName });
          }
        }
      });

      if (attrs.length) {
        attrs = attrs.sort((a, b) => parseInt(prepareAttributeForSort(a)) - parseInt(prepareAttributeForSort(b)));
        titleAttr.push({ name: variationsAttributesTitles[i], attrs: attrs, colors: colors });
      }
    }
  }

  let ranged_products = filtered_products;

  if (stateFilter.selected.length > 0) {
    stateFilter.selected.forEach((atr) => {
      let store = [];
      atr.values.forEach((v) => {
        const items = ranged_products.filter((el) => el.attributes2.includes(v));
        store.push(...items);
      });

      let newStore = Array.from(new Set(store));
      ranged_products = newStore;
    });
  }

  useEffect(() => {
    const handleFilterOpen = () => {
      const filterCont = document.querySelector('.all-product .filter .filter-cont');
      if (filterCont) {
        filterCont.classList.add('active');
      }
    };

    const handleFilterClose = () => {
      const filterCont = document.querySelector('.all-product .filter .filter-cont');
      if (filterCont) {
        filterCont.classList.remove('active');
      }
    };

    const filterOpen = document.querySelector('.all-product .filter .filter-open');
    const filterClose = document.querySelector('.all-product .filter .close');


    if (filterOpen) {
      filterOpen.addEventListener('click', handleFilterOpen);
    }

    if (filterClose) {
      filterClose.addEventListener('click', handleFilterClose);
    }

    return () => {
      if (filterOpen) {
        filterOpen.removeEventListener('click', handleFilterOpen);
      }

      if (filterClose) {
        filterClose.removeEventListener('click', handleFilterClose);
      }

    };
  }, []);


  useEffect(() => {
    const destroyRangeInputs = () => {
      jcf.destroyAll('.range-input');
    };

    const getMinPrice = (array) => {
      return Math.min(...array.map((item) => item.priceUAH));
    };

    const getMaxPrice = (array) => {
      return Math.max(...array.map((item) => item.priceUAH));
    };

    const min = ranged_products.length ? getMinPrice(ranged_products) : getMinPrice(filtered_products);
    const max = ranged_products.length ? getMaxPrice(ranged_products) : getMaxPrice(filtered_products);

    setFilter(prevState => ({
      ...prevState,
      min: min,
      max: max,
      attrbs: titleAttr
    }));
    setLoading(false);
    destroyRangeInputs();

    return () => {
      destroyRangeInputs();
    }
  }, [stateFilter.catName]);

  useEffect(() => {
    setCurrentPage(1);
  }, [stateFilter.min, stateFilter.max, stateFilter.selected]);

  if (inSort) {
    ranged_products = ranged_products.sort((a, b) => {
      if (inSort === "upcost") {
        return a.priceUAH - b.priceUAH;
      } else {
        return b.priceUAH - a.priceUAH;
      }
    });
  }

  ranged_products = absentDown(ranged_products);

  const count = ranged_products.length;
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
          <Filter stateFilter={stateFilter} changeFilter={changeFilter} products={products} />
          <div className="product-items">
            {productsCrop.map((product) => {
              return (
                <ProductCat
                  key={product.id}
                  product={product}
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
