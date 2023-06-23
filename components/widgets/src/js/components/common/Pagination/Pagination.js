import React from "react";
// import "./pagination.css";

const Pagination = ({
  itemsCount,
  pageSize,
  currentPage,
  onChangePage,
  onChangePageByArrows,
}) => {
  const pageCount = Math.ceil(itemsCount / pageSize);
  const pages = [];

  if (pageCount === 1) {
    return null;
  }

  for (let i = 0; i < pageCount; i++) {
    pages.push(i + 1);
  }

  return (
    <nav>
      <ul className="pagination">
        <li className={currentPage <= 1 ? "page-item disabled" : "page-item"}>
          <button
            className="page-link"
            onClick={() => onChangePageByArrows(-1)}
            disabled={currentPage <= 1}
          >
            &laquo;
          </button>
        </li>
        {pages.map((page) => (
          <li key={"page_" + page} className={"page-item"}>
            <button
              className={
                currentPage === page
                  ? "page-link page-link__active"
                  : "page-link"
              }
              onClick={() => onChangePage(page)}
            >
              {page}
            </button>
          </li>
        ))}
        <li className="page-item">
          <button
            className="page-link"
            disabled={currentPage >= pages.length}
            onClick={() => onChangePageByArrows(1)}
          >
            &raquo;
          </button>
        </li>
      </ul>
    </nav>
  );
};

export default Pagination;
