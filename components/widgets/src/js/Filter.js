import React, { memo, useRef, useEffect, useState } from 'react'

const Filter = memo(({ stateFilter, changeFilter, products }) => {

  // To make controlled inputs
  const [inputValues, setInputValues] = useState({
    input1: stateFilter.min || 0,
    input2: stateFilter.max || 0,
    range1: stateFilter.min,
    range2: stateFilter.max,
  });

  useEffect(() => {
    changeFilter(({
      ...stateFilter,
      setMin: inputValues.input1,
      setMax: inputValues.input2
    }))
  }, [inputValues]);

  const handleTextChange = (e, inputNumber) => {
    const { value } = e.target;
    setInputValues((prevState) => ({
      ...prevState,
      [`input${inputNumber}`]: parseInt(value),
      // [`range${inputNumber}`]: parseInt(value),
    }));

  };

  const handleRangeChange = (e, rangeNumber) => {
    const { value } = e.target;
    setInputValues((prevState) => ({
      ...prevState,
      [`range${rangeNumber}`]: parseInt(value),
      [`input${rangeNumber}`]: parseInt(value),
    }));
  };
  const { input1, input2, range1, range2 } = inputValues;

  function setPrice(e) {
    e.preventDefault();
    const setmin = document.getElementById("setmin").value;
    const setmax = document.getElementById("setmax").value;

    changeFilter({ ...stateFilter, setMin: setmin, setMax: setmax });
  }

  function selCat(e) {
    e.preventDefault();
    // Deselect all attribute for new category
    let attrs = document.querySelectorAll("input[name=attr]:checked");
    [...attrs].map((el) => (el.checked = false));
    console.log('stateFilter.catName', stateFilter.catName)
    let catName = e.target.getAttribute("data-name")
    changeFilter({
      ...stateFilter,
      catName: catName,
      selected: [],
    });
  }
  function reset() {
    changeFilter({ ...stateFilter, selected: [] });
    let attrs = document.querySelectorAll("input[name=attr]:checked");
    [...attrs].map((el) => (el.checked = false));
  }

  function checkAttr() {
    let selectedAttr = [];
    let attrs = document.querySelectorAll("input[name=attr]:checked");
    [...attrs].forEach((el) => {
      let atr = el.getAttribute("atr");
      let value = el.value;
      if (selectedAttr.length === 0) {
        selectedAttr.push({
          id: atr,
          name: stateFilter.attrbs[atr].name,
          values: [value],
        });
      } else {
        let temp = selectedAttr.find((fe) => fe.id === atr);
        if (temp !== undefined) {
          temp.values.push(value);
        } else {
          selectedAttr.push({
            id: atr,
            name: stateFilter.attrbs[atr].name,
            values: [value],
          });
        }
      }
    });
    changeFilter({ ...stateFilter, selected: selectedAttr });
  }

  // useEffect(() => {

  // }, [inputValues.input1, inputValues.input2])

  useEffect(() => {

    jcf.replace(jQuery(".filter-item .checkbox-item input[type=checkbox]"));

    const rangeInput = document.querySelectorAll(".price .range-input input"),
      priceInput = document.querySelectorAll(".price .price-input input"),
      range = document.querySelector(".price .slider .progress");
    let priceGap = 10;

    // priceInput.forEach((input) => {
    //   input.addEventListener("input", (e) => {

    //     let minPrice = parseInt(priceInput[0].value),
    //       maxPrice = parseInt(priceInput[1].value);

    // if (maxPrice - minPrice >= priceGap && maxPrice <= rangeInput[1].max) {
    //   if (e.target.className === "input-min") {
    //     rangeInput[0].value = minPrice;
    //     range.style.left = (minPrice / rangeInput[0].max) * 100 + "%";
    //   } else {
    //     rangeInput[1].value = maxPrice;
    //     range.style.right =
    //       100 - (maxPrice / rangeInput[1].max) * 100 + "%";
    //   }
    // }
    //   });
    // });

    rangeInput.forEach((input) => {
      input.addEventListener("input", (e) => {
        let minVal = parseInt(rangeInput[0].value),
          maxVal = parseInt(rangeInput[1].value);

        if (maxVal - minVal < priceGap) {
          if (e.target.className === "range-min") {
            rangeInput[0].value = maxVal - priceGap;
          } else {
            rangeInput[1].value = minVal + priceGap;
          }
        } else {
          const value1 = rangeInput[0].value;
          const value2 = rangeInput[1].value;

          priceInput[0].value = minVal;
          priceInput[1].value = maxVal;
          range.style.left =
            ((value1 - rangeInput[0].min) /
              (rangeInput[0].max - rangeInput[0].min)) *
            100 +
            "%";
          range.style.right =
            100 -
            ((value2 - rangeInput[1].min) /
              (rangeInput[1].max - rangeInput[1].min)) *
            100 +
            "%";
        }
      });
    });

  }, [stateFilter]);


  return (
    <div className="filter">
      <span className="filter-open">
        <svg width="22" height="20" viewBox="0 0 26 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 5.33333H5" stroke="#141B34" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"></path><path d="M1 18.6667H9" stroke="#141B34" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"></path><path d="M21 18.6667H25" stroke="#141B34" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"></path><path d="M17 5.33333H25" stroke="#141B34" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"></path><path d="M5 5.33333C5 4.09083 5 3.46957 5.20299 2.97951C5.47364 2.32611 5.99277 1.80697 6.64617 1.53632C7.13624 1.33333 7.75749 1.33333 9 1.33333C10.2425 1.33333 10.8637 1.33333 11.3539 1.53632C12.0072 1.80697 12.5264 2.32611 12.7971 2.97951C13 3.46957 13 4.09083 13 5.33333C13 6.57584 13 7.19709 12.7971 7.68716C12.5264 8.34056 12.0072 8.85969 11.3539 9.13035C10.8637 9.33333 10.2425 9.33333 9 9.33333C7.75749 9.33333 7.13624 9.33333 6.64617 9.13035C5.99277 8.85969 5.47364 8.34056 5.20299 7.68716C5 7.19709 5 6.57584 5 5.33333Z" stroke="#222222" strokeWidth="1.5"></path><path d="M13 18.6667C13 17.4241 13 16.8029 13.2029 16.3128C13.4736 15.6595 13.9928 15.1403 14.6461 14.8696C15.1363 14.6667 15.7575 14.6667 17 14.6667C18.2425 14.6667 18.8637 14.6667 19.3539 14.8696C20.0072 15.1403 20.5264 15.6595 20.7971 16.3128C21 16.8029 21 17.4241 21 18.6667C21 19.9092 21 20.5304 20.7971 21.0205C20.5264 21.6739 20.0072 22.1931 19.3539 22.4637C18.8637 22.6667 18.2425 22.6667 17 22.6667C15.7575 22.6667 15.1363 22.6667 14.6461 22.4637C13.9928 22.1931 13.4736 21.6739 13.2029 21.0205C13 20.5304 13 19.9092 13 18.6667Z" stroke="#222222" strokeWidth="1.5"></path></svg>
        {tehnokrat.strings['Filter']}
        <svg
          className="icon-arrow"
          width="12"
          height="7"
          viewBox="0 0 12 7"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M1 1L5.41075 5.56844C5.6885 5.85612 5.82742 6 6 6C6.17258 6 6.3115 5.85612 6.58925 5.56844L11 1"
            stroke="#343434"
            strokeWidth="1.5"
            strokeLinecap="round"
            strokeLinejoin="round"
          ></path>
        </svg>
      </span>
      <div className="filter-cont">
        <div className="close">
          <span>Готово</span>
        </div>
        <div className="filter-section">
          <p className="title">{tehnokrat.strings['Model range']}</p>
          <div className="model">
            {products.map((e) => {
              let act = e.name === stateFilter.catName ? "active" : "";
              return (
                <a
                  href={e.url}
                  key={e.name}
                  className={act}
                  data-name={e.name}
                // onClick={selCat}
                >
                  {e.name}
                </a>
              );
            })}
          </div>
        </div>
        {stateFilter.selected !== undefined &&
          stateFilter.selected.length > 0 ? (
          <div className="filter-section chosen-items">

            <p className="title">
              <svg width="22" height="20" viewBox="0 0 26 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 5.33333H5" stroke="#141B34" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"></path><path d="M1 18.6667H9" stroke="#141B34" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"></path><path d="M21 18.6667H25" stroke="#141B34" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"></path><path d="M17 5.33333H25" stroke="#141B34" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"></path><path d="M5 5.33333C5 4.09083 5 3.46957 5.20299 2.97951C5.47364 2.32611 5.99277 1.80697 6.64617 1.53632C7.13624 1.33333 7.75749 1.33333 9 1.33333C10.2425 1.33333 10.8637 1.33333 11.3539 1.53632C12.0072 1.80697 12.5264 2.32611 12.7971 2.97951C13 3.46957 13 4.09083 13 5.33333C13 6.57584 13 7.19709 12.7971 7.68716C12.5264 8.34056 12.0072 8.85969 11.3539 9.13035C10.8637 9.33333 10.2425 9.33333 9 9.33333C7.75749 9.33333 7.13624 9.33333 6.64617 9.13035C5.99277 8.85969 5.47364 8.34056 5.20299 7.68716C5 7.19709 5 6.57584 5 5.33333Z" fill="#77bd00" stroke="#222222" strokeWidth="1.5"></path><path d="M13 18.6667C13 17.4241 13 16.8029 13.2029 16.3128C13.4736 15.6595 13.9928 15.1403 14.6461 14.8696C15.1363 14.6667 15.7575 14.6667 17 14.6667C18.2425 14.6667 18.8637 14.6667 19.3539 14.8696C20.0072 15.1403 20.5264 15.6595 20.7971 16.3128C21 16.8029 21 17.4241 21 18.6667C21 19.9092 21 20.5304 20.7971 21.0205C20.5264 21.6739 20.0072 22.1931 19.3539 22.4637C18.8637 22.6667 18.2425 22.6667 17 22.6667C15.7575 22.6667 15.1363 22.6667 14.6461 22.4637C13.9928 22.1931 13.4736 21.6739 13.2029 21.0205C13 20.5304 13 19.9092 13 18.6667Z" fill="#77bd00" stroke="#222222" strokeWidth="1.5"></path></svg>
              {tehnokrat.strings['You chose']}:
            </p>
            <ul>
              {stateFilter.selected.map((el) => {
                return el.values.map((v) => {
                  return (
                    <li key={el.id + v}>
                      {el.name}:&nbsp;
                      {v.startsWith("#") ? stateFilter.attrbs[0].colors.find((el) => el[v])[v] : v}

                      {/* {v.startsWith("#") ? <i style={{
                        backgroundColor: v.startsWith('#')
                          ? v : 'inherit'
                      }}></i> : v} */}


                      <button
                        className="del"
                        onClick={() =>
                          document
                            .querySelector('input[value="' + v + '"]')
                            .click()
                        }
                      >
                        <svg
                          width="7"
                          height="7"
                          viewBox="0 0 7 7"
                          fill="none"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <path
                            d="M6.33333 1L3.66667 3.66667M3.66667 3.66667L1 6.33333M3.66667 3.66667L6.33333 6.33333M3.66667 3.66667L1 1"
                            stroke="#343434"
                            strokeLinecap="round"
                            strokeLinejoin="round"
                          ></path>
                        </svg>
                      </button>
                    </li>
                  );
                });
              })}
            </ul>
          </div>
        ) : (
          ""
        )}
        {products.length == 1 &&
          <div className="filter-section">
            {stateFilter.selected.length ? <p className="reset" onClick={reset}>{tehnokrat.strings['Reset']}</p> : <></>}
            <p className="title">
              <svg width="22" height="20" viewBox="0 0 26 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 5.33333H5" stroke="#141B34" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"></path><path d="M1 18.6667H9" stroke="#141B34" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"></path><path d="M21 18.6667H25" stroke="#141B34" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"></path><path d="M17 5.33333H25" stroke="#141B34" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"></path><path d="M5 5.33333C5 4.09083 5 3.46957 5.20299 2.97951C5.47364 2.32611 5.99277 1.80697 6.64617 1.53632C7.13624 1.33333 7.75749 1.33333 9 1.33333C10.2425 1.33333 10.8637 1.33333 11.3539 1.53632C12.0072 1.80697 12.5264 2.32611 12.7971 2.97951C13 3.46957 13 4.09083 13 5.33333C13 6.57584 13 7.19709 12.7971 7.68716C12.5264 8.34056 12.0072 8.85969 11.3539 9.13035C10.8637 9.33333 10.2425 9.33333 9 9.33333C7.75749 9.33333 7.13624 9.33333 6.64617 9.13035C5.99277 8.85969 5.47364 8.34056 5.20299 7.68716C5 7.19709 5 6.57584 5 5.33333Z" fill="#77bd00" stroke="#222222" strokeWidth="1.5"></path><path d="M13 18.6667C13 17.4241 13 16.8029 13.2029 16.3128C13.4736 15.6595 13.9928 15.1403 14.6461 14.8696C15.1363 14.6667 15.7575 14.6667 17 14.6667C18.2425 14.6667 18.8637 14.6667 19.3539 14.8696C20.0072 15.1403 20.5264 15.6595 20.7971 16.3128C21 16.8029 21 17.4241 21 18.6667C21 19.9092 21 20.5304 20.7971 21.0205C20.5264 21.6739 20.0072 22.1931 19.3539 22.4637C18.8637 22.6667 18.2425 22.6667 17 22.6667C15.7575 22.6667 15.1363 22.6667 14.6461 22.4637C13.9928 22.1931 13.4736 21.6739 13.2029 21.0205C13 20.5304 13 19.9092 13 18.6667Z" fill="#77bd00" stroke="#222222" strokeWidth="1.5"></path></svg>
              {tehnokrat.strings['Filter']}
            </p>
            {/* <div className="filter-item price">
            <p className="filter-title">Ціна</p>
            <div className="price-input">
              <input
                type="number"
                className="input-min"
                id="setmin"
                min={stateFilter.min}
                value={input1}
                max={stateFilter.max}
                step="10"
                onChange={(e) => handleTextChange(e, 1)}
              />
              <input
                type="number"
                className="input-max"
                id="setmax"
                min={stateFilter.min}
                value={input2}
                max={stateFilter.max}
                step="10"
                onChange={(e) => handleTextChange(e, 2)}
              />
              <input type="button" value="Ok" onClick={setPrice} />
            </div>
            <div className="slider">
              <div className="progress"></div>
            </div>
            <div className="range-input">
              <input
                type="range"
                className="range-min"
                min={stateFilter.min}
                max={stateFilter.max}
                value={range1}
                step="10"
                onChange={(e) => handleRangeChange(e, 1)}
              />
              <input
                type="range"
                className="range-max"
                min={stateFilter.min}
                max={stateFilter.max}
                value={range2}
                step="10"
                onChange={(e) => handleRangeChange(e, 2)}
              />
            </div>
          </div> */}
            <div className="filter-item">
              {stateFilter.attrbs !== undefined &&
                stateFilter.attrbs.map((ats, inx) => {
                  return (
                    <React.Fragment key={"a" + inx}>
                      <p className="filter-title"> {ats.name}</p>
                      <div className="ckeckbox-group">
                        {ats.attrs.map((value, i) => {
                          return (
                            <div
                              key={"v" + i}
                              className={
                                value.startsWith("#")
                                  ? "attribute checkbox-item color"
                                  : "attribute checkbox-item"
                              }
                            >
                              <input
                                id={"a" + inx + "v" + i}
                                name="attr"
                                type="checkbox"
                                atr={inx}
                                value={value}
                                onChange={checkAttr}
                              />
                              <label
                                htmlFor={"a" + inx + "v" + i}
                                style={{
                                  backgroundColor: value.startsWith("#")
                                    ? value
                                    : "inherit",
                                }}
                              >
                                {value.startsWith("#") === false && value}
                                {value.startsWith("#") ? <i> {ats.colors.find((el) => el[value])[value]}</i> : ''}
                              </label>
                            </div>
                          );
                        })}
                      </div>
                    </React.Fragment>
                  );
                })}
            </div>
          </div>
        }
      </div>
      <div className="other-side"></div>
    </div>
  );
});

export default Filter;
