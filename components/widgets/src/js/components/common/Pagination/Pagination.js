import React from "react";

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
        {currentPage > 2 ?
          <li className="page-item">
            <button
              className="page-link"
              onClick={() => onChangePage(1)}
            >
              {1}
            </button>
          </li> : ''
        }

        {currentPage > 3 ?
          <li className="page-item">
            ...
          </li> : ''
        }
        {(currentPage - 1) > 0 ?
          <li className="page-item">
            <button
              className="page-link"
              onClick={() => onChangePage(currentPage - 1)}
            >
              {currentPage - 1}
            </button>
          </li> : ''
        }
        <li className="page-item">
          <button
            className="page-link page-link__active"
          >
            {currentPage}
          </button>
        </li>
        {(currentPage + 1 < pageCount) ?
          <li className="page-item">
            <button
              className="page-link"
              onClick={() => onChangePage(currentPage + 1)}
            >
              {currentPage + 1}
            </button>
          </li> : ''
        }
        {(currentPage < pageCount) ?
          <>
            {
              (currentPage < pageCount - 2) ?
                <li className="page-item">
                  ...
                </li> : ''
            }
            <li className="page-item">
              <button
                className="page-link"
                onClick={() => onChangePage(pageCount)}
              >
                {pageCount}
              </button>
            </li>
            <li className="page-item">
              <button
                className="page-link"
                disabled={currentPage >= pages.length}
                onClick={() => onChangePageByArrows(1)}
              >
                &raquo;
              </button>
            </li>
          </>
          : ''
        }

      </ul>
    </nav>
  );
};

export default Pagination;
