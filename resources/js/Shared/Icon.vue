<template>
  <svg
    v-if="name === 'apple'"
    xmlns="http://www.w3.org/2000/svg"
    width="100"
    height="100"
    viewBox="0 0 100 100"
  >
    <g fill-rule="nonzero">
      <path
        d="M46.173 19.967C49.927-1.838 19.797-.233 14.538.21c-.429.035-.648.4-.483.8 2.004 4.825 14.168 31.66 32.118 18.957zm13.18 1.636c1.269-.891 1.35-1.614.047-2.453l-2.657-1.71c-.94-.607-1.685-.606-2.532.129-5.094 4.42-7.336 9.18-8.211 15.24 1.597.682 3.55.79 5.265.328 1.298-4.283 3.64-8.412 8.088-11.534z"
      />
      <path
        d="M88.588 67.75c9.65-27.532-13.697-45.537-35.453-32.322-1.84 1.118-4.601 1.118-6.441 0-21.757-13.215-45.105 4.79-35.454 32.321 5.302 15.123 17.06 39.95 37.295 29.995.772-.38 1.986-.38 2.758 0 20.235 9.955 31.991-14.872 37.295-29.995z"
      />
    </g>
  </svg>
  <svg
    v-else-if="name === 'book'"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 20 20"
  >
    <path
      d="M6 4H5a1 1 0 1 1 0-2h11V1a1 1 0 0 0-1-1H4a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V5a1 1 0 0 0-1-1h-7v8l-2-2-2 2V4z"
    />
  </svg>
  <svg
    v-else-if="name === 'cheveron-down'"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 20 20"
  >
    <path
      d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
    />
  </svg>
  <svg
    v-else-if="name === 'cheveron-right'"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 20 20"
  >
    <polygon
      points="12.95 10.707 13.657 10 8 4.343 6.586 5.757 10.828 10 6.586 14.243 8 15.657 12.95 10.707"
    />
  </svg>
  <svg
    v-else-if="name === 'dashboard'"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 20 20"
  >
    <path
      d="M10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm-5.6-4.29a9.95 9.95 0 0 1 11.2 0 8 8 0 1 0-11.2 0zm6.12-7.64l3.02-3.02 1.41 1.41-3.02 3.02a2 2 0 1 1-1.41-1.41z"
    />
  </svg>
  <svg
    v-else-if="name === 'location'"
    xmlns="http://www.w3.org/2000/svg"
    width="100"
    height="100"
    viewBox="0 0 100 100"
  >
    <path
      d="M10 20S3 10.87 3 7a7 7 0 1 1 14 0c0 3.87-7 13-7 13zm0-11a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"
    />
  </svg>
  <svg
    v-else-if="name === 'farm'"
    xmlns="http://www.w3.org/2000/svg"
    width="100"
    height="100"
    viewBox="0 0 100 100"
  >
    <path
      fill-rule="evenodd"
      d="M16 12L9 2L2 12H3.86L0 18H7V22H11V18H18L14.14 12H16M20.14 12H22L15 2L12.61 5.41L17.92 13H15.97L19.19 18H24L20.14 12M13 19H17V22H13V19Z"
    />
  </svg>
  <svg
    v-else-if="name === 'printer'"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 20 20"
  >
    <path
      d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z"
    />
  </svg>
  <svg
    v-else-if="name === 'shopping-cart'"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 20 20"
  >
    <path
      d="M4 2h16l-3 9H4a1 1 0 1 0 0 2h13v2H4a3 3 0 0 1 0-6h.33L3 5 2 2H0V0h3a1 1 0 0 1 1 1v1zm1 18a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm10 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"
    />
  </svg>
  <svg
    v-else-if="name === 'store-front'"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 20 20"
  >
    <path
      d="M18 9.87V20H2V9.87a4.25 4.25 0 0 0 3-.38V14h10V9.5a4.26 4.26 0 0 0 3 .37zM3 0h4l-.67 6.03A3.43 3.43 0 0 1 3 9C1.34 9 .42 7.73.95 6.15L3 0zm5 0h4l.7 6.3c.17 1.5-.91 2.7-2.42 2.7h-.56A2.38 2.38 0 0 1 7.3 6.3L8 0zm5 0h4l2.05 6.15C19.58 7.73 18.65 9 17 9a3.42 3.42 0 0 1-3.33-2.97L13 0z"
    />
  </svg>
  <svg
    v-else-if="name === 'trash'"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 20 20"
  >
    <path
      d="M6 2l2-2h4l2 2h4v2H2V2h4zM3 6h14l-1 14H4L3 6zm5 2v10h1V8H8zm3 0v10h1V8h-1z"
    />
  </svg>
  <svg
    v-else-if="name === 'users'"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 20 20"
  >
    <path
      d="M7 8a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0 1c2.15 0 4.2.4 6.1 1.09L12 16h-1.25L10 20H4l-.75-4H2L.9 10.09A17.93 17.93 0 0 1 7 9zm8.31.17c1.32.18 2.59.48 3.8.92L18 16h-1.25L16 20h-3.96l.37-2h1.25l1.65-8.83zM13 0a4 4 0 1 1-1.33 7.76 5.96 5.96 0 0 0 0-7.52C12.1.1 12.53 0 13 0z"
    />
  </svg>
  <svg
    v-else-if="name === 'employee'"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 20 20"
  >
    <path
      d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"
    />
  </svg>
  <svg
    v-else-if="name === 'location'"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 20 20"
  >
    <path
      fill-rule="evenodd"
      d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
      clip-rule="evenodd"
    />
  </svg>
  <svg
    v-else-if="name === 'customer'"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 20 20"
  >
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      stroke-width="2"
      d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
    />
  </svg>
  <svg
    v-else-if="name === 'download'"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 24 24"
  >
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      stroke-width="2"
      d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"
    />
  </svg>
  <svg
    v-else-if="name === 'service'"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 24 24"
  >
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      stroke-width="2"
      d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"
    />
  </svg>

  <svg
    v-else-if="name === 'bottom-arrow'"
    xmlns="http://www.w3.org/2000/svg"
    width="8.095"
    height="4.5"
    viewBox="0 0 8.095 4.5"
  >
    <path
      id="ic_seta"
      d="M3.733,7.964.176,4.407a.453.453,0,0,1-.044-.039.457.457,0,0,1,0-.642.453.453,0,0,1,.048-.042L3.733.132a.449.449,0,0,1,.635.635l-3.28,3.28,3.28,3.281a.449.449,0,1,1-.635.635Z"
      transform="translate(-0.001 4.5) rotate(-90)"
      fill="#bfbfbf"
    />
  </svg>

  <svg
    v-else-if="name === 'rightArrow'"
    xmlns="http://www.w3.org/2000/svg"
    width="16.301"
    height="14.132"
    viewBox="0 0 16.301 14.132"
  >
    <g
      id="Group_574"
      data-name="Group 574"
      transform="translate(-440.119 -342.69)"
    >
      <rect
        id="line"
        width="16"
        height="1.5"
        rx="0.75"
        transform="translate(456.119 350.491) rotate(180)"
      />
      <rect
        id="line-2"
        data-name="line"
        width="10"
        height="1.5"
        rx="0.75"
        transform="translate(455.359 350.821) rotate(-135)"
      />
      <rect
        id="line-3"
        data-name="line"
        width="10"
        height="1.5"
        rx="0.75"
        transform="translate(456.42 349.75) rotate(135)"
      />
    </g>
  </svg>

  <svg
    v-else-if="name === 'leftArrow'"
    xmlns="http://www.w3.org/2000/svg"
    width="16"
    height="13.51"
    viewBox="0 0 16 13.51"
  >
    <path
      id="union"
      d="M6.241,13.29.311,7.361A.749.749,0,0,1,.1,7.116.75.75,0,0,1,.359,6.1L6.241.22A.75.75,0,0,1,7.3,1.281L2.591,5.991H15.25a.75.75,0,1,1,0,1.5H2.562L7.3,12.23a.75.75,0,1,1-1.061,1.06Z"
      transform="translate(0 0)"
    />
  </svg>
  <svg
    v-else-if="name === 'resultTODOs'"
    xmlns="http://www.w3.org/2000/svg"
    width="23.344"
    height="31.048"
    viewBox="0 0 23.344 31.048"
  >
    <g id="ic-exames" transform="translate(4.15 0.15)">
      <path
        id="Path_672"
        data-name="Path 672"
        d="M15.667,2.088H10.725a3.5,3.5,0,0,0-6.407,0H-.623A3.381,3.381,0,0,0-4,5.465V27.371A3.381,3.381,0,0,0-.623,30.748h16.29a3.381,3.381,0,0,0,3.377-3.377V5.465A3.381,3.381,0,0,0,15.667,2.088ZM4.875,3.625a.769.769,0,0,0,.751-.6,1.954,1.954,0,0,1,3.793,0,.768.768,0,0,0,.751.6h1.524V4.3A1.841,1.841,0,0,1,9.854,6.139H5.19A1.842,1.842,0,0,1,3.35,4.3V3.625ZM17.506,27.371a1.841,1.841,0,0,1-1.84,1.84H-.623a1.841,1.841,0,0,1-1.839-1.84V5.465A1.842,1.842,0,0,1-.623,3.625H1.813V4.3A3.381,3.381,0,0,0,5.19,7.676H9.854A3.381,3.381,0,0,0,13.231,4.3V3.625h2.436a1.842,1.842,0,0,1,1.84,1.84Zm0,0"
        stroke="#f4f5f4"
        stroke-miterlimit="10"
        stroke-width="0.3"
      />
      <path
        id="Path_673"
        data-name="Path 673"
        d="M109.174,227.66l-5.046,4.927-2.563-2.5a.769.769,0,0,0-1.084,1.09l.01.01,3.1,3.026a.769.769,0,0,0,1.074,0l5.583-5.451a.769.769,0,0,0-1.074-1.1Zm0,0"
        transform="translate(-97.848 -213.457)"
        stroke="#f4f5f4"
        stroke-miterlimit="10"
        stroke-width="0.3"
      />
    </g>
  </svg>

  <svg
    v-else-if="name === 'calendar'"
    xmlns="http://www.w3.org/2000/svg"
    width="29.144"
    height="29.144"
    viewBox="0 0 29.144 29.144"
  >
    <g id="calendar" transform="translate(25.4 -103.6)">
      <g id="ic_agendamentos" transform="translate(-25 104)">
        <g id="Group_437" data-name="Group 437">
          <ellipse
            id="Ellipse_14"
            data-name="Ellipse 14"
            cx="1.308"
            cy="0.872"
            rx="1.308"
            ry="0.872"
            transform="translate(20.059 10.901)"
            stroke="#fff"
            stroke-width="0.8"
          />
          <path
            id="Path_591"
            data-name="Path 591"
            d="M23.915,2.214H22.476V1.107a1.107,1.107,0,0,0-2.214,0V2.214H15.224V1.107a1.107,1.107,0,0,0-2.214,0V2.214H8.027V1.107a1.107,1.107,0,0,0-2.214,0V2.214H4.429A4.434,4.434,0,0,0,0,6.643V23.915a4.434,4.434,0,0,0,4.429,4.429H12.9a1.107,1.107,0,0,0,0-2.214H4.429a2.217,2.217,0,0,1-2.214-2.214V6.643A2.217,2.217,0,0,1,4.429,4.429H5.813V5.536a1.107,1.107,0,0,0,2.214,0V4.429h4.982V5.536a1.107,1.107,0,0,0,2.214,0V4.429h5.038V5.536a1.107,1.107,0,0,0,2.214,0V4.429h1.439a2.217,2.217,0,0,1,2.214,2.214v6.311a1.107,1.107,0,0,0,2.214,0V6.643A4.434,4.434,0,0,0,23.915,2.214Z"
            stroke="#fff"
            stroke-width="0.8"
          />
          <path
            id="Path_592"
            data-name="Path 592"
            d="M276.7,270a6.7,6.7,0,1,0,6.7,6.7A6.706,6.706,0,0,0,276.7,270Zm0,11.182a4.484,4.484,0,1,1,4.484-4.484A4.489,4.489,0,0,1,276.7,281.182Z"
            transform="translate(-255.053 -255.053)"
            stroke="#fff"
            stroke-width="0.8"
          />
          <path
            id="Path_593"
            data-name="Path 593"
            d="M373.713,332.27h-.5v-1.163a1.107,1.107,0,0,0-2.214,0v2.27a1.107,1.107,0,0,0,1.107,1.107h1.605a1.107,1.107,0,0,0,0-2.214Z"
            transform="translate(-350.462 -311.732)"
            stroke="#fff"
            stroke-width="0.8"
          />
          <circle
            id="Ellipse_15"
            data-name="Ellipse 15"
            cx="0.872"
            cy="0.872"
            r="0.872"
            transform="translate(15.698 10.901)"
            stroke="#fff"
            stroke-width="0.8"
          />
          <circle
            id="Ellipse_16"
            data-name="Ellipse 16"
            cx="1.308"
            cy="1.308"
            r="1.308"
            transform="translate(10.465 15.262)"
            stroke="#fff"
            stroke-width="0.8"
          />
          <circle
            id="Ellipse_17"
            data-name="Ellipse 17"
            cx="0.872"
            cy="0.872"
            r="0.872"
            transform="translate(6.105 10.901)"
            stroke="#fff"
            stroke-width="0.8"
          />
          <ellipse
            id="Ellipse_18"
            data-name="Ellipse 18"
            cx="0.872"
            cy="1.308"
            rx="0.872"
            ry="1.308"
            transform="translate(6.105 15.262)"
            stroke="#fff"
            stroke-width="0.8"
          />
          <circle
            id="Ellipse_19"
            data-name="Ellipse 19"
            cx="0.872"
            cy="0.872"
            r="0.872"
            transform="translate(6.105 20.495)"
            stroke="#fff"
            stroke-width="0.8"
          />
          <ellipse
            id="Ellipse_20"
            data-name="Ellipse 20"
            cx="1.308"
            cy="0.872"
            rx="1.308"
            ry="0.872"
            transform="translate(10.465 20.495)"
            stroke="#fff"
            stroke-width="0.8"
          />
          <ellipse
            id="Ellipse_21"
            data-name="Ellipse 21"
            cx="1.308"
            cy="0.872"
            rx="1.308"
            ry="0.872"
            transform="translate(10.465 10.901)"
            stroke="#fff"
            stroke-width="0.8"
          />
        </g>
      </g>
    </g>
  </svg>

  <svg
    v-else-if="name === 'msg-info'"
    xmlns="http://www.w3.org/2000/svg"
    width="18.178"
    height="18.508"
    viewBox="0 0 18.178 18.508"
  >
    <g id="ic_chat" transform="translate(-3.266 0.1)">
      <g id="Group_445" data-name="Group 445" transform="translate(3.366)">
        <g id="Group_444" data-name="Group 444" transform="translate(0)">
          <path
            id="Path_652"
            data-name="Path 652"
            d="M12.4,0s-.015,0-.029,0A8.981,8.981,0,0,0,5.323,14.583l-1.31,3.041a.488.488,0,0,0,.255.643.5.5,0,0,0,.28.034l4.8-.844a8.654,8.654,0,0,0,2.992.52A8.989,8.989,0,0,0,12.4,0Zm-.049,17A7.874,7.874,0,0,1,9.576,16.5a.47.47,0,0,0-.26-.025l-4.042.706,1.069-2.487a.493.493,0,0,0-.078-.515A7.842,7.842,0,0,1,4.9,11.91,8.009,8.009,0,1,1,20.368,8.947v.029A8.03,8.03,0,0,1,12.348,17Z"
            transform="translate(-3.366)"
            stroke="#000"
            stroke-width="0.2"
          />
        </g>
      </g>
      <g
        id="Group_447"
        data-name="Group 447"
        transform="translate(8.826 7.196)"
      >
        <g id="Group_446" data-name="Group 446">
          <path
            id="Path_653"
            data-name="Path 653"
            d="M119.938,146.7H115.49c-.453,0-.824.221-.824.491s.371.491.824.491h4.448c.453,0,.824-.221.824-.491S120.391,146.7,119.938,146.7Z"
            transform="translate(-114.666 -146.7)"
            stroke="#000"
            stroke-width="0.2"
          />
        </g>
      </g>
      <g
        id="Group_449"
        data-name="Group 449"
        transform="translate(8.826 9.649)"
      >
        <g id="Group_448" data-name="Group 448">
          <path
            id="Path_654"
            data-name="Path 654"
            d="M121.239,196.7h-6.083a.491.491,0,0,0,0,.981h6.083a.491.491,0,1,0,0-.981Z"
            transform="translate(-114.666 -196.7)"
            stroke="#000"
            stroke-width="0.2"
          />
        </g>
      </g>
    </g>
  </svg>
  <svg
    v-else-if="name === 'painel'"
    xmlns="http://www.w3.org/2000/svg"
    width="39.465"
    height="39.013"
    viewBox="0 0 39.465 39.013"
  >
    <g id="item" transform="translate(-65.073 -353.8)">
      <path
        id="ic-painel"
        d="M38.072,16.795l0,0L22.318,1.041a3.554,3.554,0,0,0-5.027,0L1.548,16.784l-.016.016A3.553,3.553,0,0,0,3.9,22.856c.037,0,.073.005.11.005h.627V34.453a4.165,4.165,0,0,0,4.161,4.16h6.162a1.131,1.131,0,0,0,1.131-1.131V28.394a1.9,1.9,0,0,1,1.9-1.9h3.635a1.9,1.9,0,0,1,1.9,1.9v9.088a1.131,1.131,0,0,0,1.131,1.131h6.162a4.165,4.165,0,0,0,4.161-4.16V22.862h.582a3.555,3.555,0,0,0,2.516-6.067Zm-1.6,3.426a1.284,1.284,0,0,1-.914.379H33.842a1.131,1.131,0,0,0-1.131,1.131V34.453a1.9,1.9,0,0,1-1.9,1.9H25.782V28.394a4.165,4.165,0,0,0-4.161-4.161H17.987a4.165,4.165,0,0,0-4.161,4.161V36.35H8.795a1.9,1.9,0,0,1-1.9-1.9V21.73A1.131,1.131,0,0,0,5.766,20.6H4.082l-.053,0a1.291,1.291,0,0,1-.89-2.2h0l15.75-15.75a1.292,1.292,0,0,1,1.827,0L36.465,18.388l.007.007A1.3,1.3,0,0,1,36.47,20.22Zm0,0"
        transform="translate(65.001 354)"
        stroke="#6a6bdb"
        stroke-width="0.4"
      />
    </g>
  </svg>
  <svg
    v-else-if="name === 'user-avatar'"
    xmlns="http://www.w3.org/2000/svg"
    width="28.044"
    height="31.965"
    viewBox="0 0 28.044 31.965"
  >
    <g id="ic-paciente" transform="translate(-31.7 0.3)">
      <g id="Group_550" data-name="Group 550" transform="translate(32 1.96)">
        <g id="Group_549" data-name="Group 549">
          <path
            id="Path_1418"
            data-name="Path 1418"
            d="M54.716,51.815l-4.287-.859A.982.982,0,0,1,49.643,50v-.984a11.557,11.557,0,0,0,.821-1.462,8.38,8.38,0,0,1,.667-1.184,6.325,6.325,0,0,0,1.988-3.138,2.917,2.917,0,0,0-.284-2.3,17.3,17.3,0,0,0-.333-3.977,4.31,4.31,0,0,0-1.019-3.156,3.028,3.028,0,0,0-1.819-.763A3.036,3.036,0,0,1,49,32.874,6.863,6.863,0,0,0,45.685,32a7.711,7.711,0,0,0-6.828,4.687,11.578,11.578,0,0,0-.269,3.548l-.025.69a3.127,3.127,0,0,0-.249,2.3,6.33,6.33,0,0,0,1.99,3.14,8.878,8.878,0,0,1,.672,1.19,12.014,12.014,0,0,0,.825,1.458V50a.982.982,0,0,1-.786.961l-4.287.859A5.9,5.9,0,0,0,32,57.58v1.864a1.962,1.962,0,0,0,1.96,1.96H57.484a1.962,1.962,0,0,0,1.96-1.96V57.58A5.9,5.9,0,0,0,54.716,51.815Zm2.768,7.629H33.96V57.58a3.93,3.93,0,0,1,3.15-3.844l4.287-.859A2.944,2.944,0,0,0,43.762,50V48.663a.979.979,0,0,0-.292-.7,8.325,8.325,0,0,1-.747-1.3,7.072,7.072,0,0,0-1.049-1.7,4.552,4.552,0,0,1-1.441-2.135,1.253,1.253,0,0,1,.074-1.013,1.875,1.875,0,0,0,.251-.89l-.01-.727a11.651,11.651,0,0,1,.153-2.844A5.748,5.748,0,0,1,45.7,33.96a4.8,4.8,0,0,1,2.35.625,4.011,4.011,0,0,0,1.29.374,3.505,3.505,0,0,1,.7.159c.265.286.484.635.5,1.982a1.062,1.062,0,0,0,.039.261,15.4,15.4,0,0,1,.292,3.562,1.875,1.875,0,0,0,.251.89,1.238,1.238,0,0,1,.073,1.017,4.528,4.528,0,0,1-1.437,2.131,6.814,6.814,0,0,0-1.043,1.7,8.588,8.588,0,0,1-.743,1.3.979.979,0,0,0-.292.7V50a2.95,2.95,0,0,0,2.364,2.884l4.287.859a3.927,3.927,0,0,1,3.15,3.842Z"
            transform="translate(-32 -32)"
            stroke="#fff"
            stroke-width="0.6"
          />
        </g>
      </g>
      <g id="Group_552" data-name="Group 552" transform="translate(32)">
        <g id="Group_551" data-name="Group 551">
          <path
            id="Path_1419"
            data-name="Path 1419"
            d="M36.9,0H32.98A.981.981,0,0,0,32,.98V6.861a.98.98,0,0,0,1.96,0V1.96H36.9A.98.98,0,0,0,36.9,0Z"
            transform="translate(-32)"
            stroke="#fff"
            stroke-width="0.6"
          />
        </g>
      </g>
      <g id="Group_554" data-name="Group 554" transform="translate(32 11.762)">
        <g id="Group_553" data-name="Group 553">
          <path
            id="Path_1420"
            data-name="Path 1420"
            d="M36.9,197.881H33.96v-4.9a.98.98,0,0,0-1.96,0v5.881a.981.981,0,0,0,.98.98H36.9a.98.98,0,0,0,0-1.96Z"
            transform="translate(-32 -192)"
            stroke="#fff"
            stroke-width="0.6"
          />
        </g>
      </g>
      <g id="Group_556" data-name="Group 556" transform="translate(53.563)">
        <g id="Group_555" data-name="Group 555">
          <path
            id="Path_1421"
            data-name="Path 1421"
            d="M388.9,0H384.98a.98.98,0,1,0,0,1.96h2.94v4.9a.98.98,0,1,0,1.96,0V.98A.98.98,0,0,0,388.9,0Z"
            transform="translate(-384)"
            stroke="#fff"
            stroke-width="0.6"
          />
        </g>
      </g>
      <g
        id="Group_558"
        data-name="Group 558"
        transform="translate(53.563 11.762)"
      >
        <g id="Group_557" data-name="Group 557">
          <path
            id="Path_1422"
            data-name="Path 1422"
            d="M388.9,192a.981.981,0,0,0-.98.98v4.9h-2.94a.98.98,0,1,0,0,1.96H388.9a.98.98,0,0,0,.98-.98V192.98A.98.98,0,0,0,388.9,192Z"
            transform="translate(-384 -192)"
            stroke="#fff"
            stroke-width="0.6"
          />
        </g>
      </g>
    </g>
  </svg>
  <svg
    v-else-if="name === 'chat'"
    xmlns="http://www.w3.org/2000/svg"
    width="28.178"
    height="28.508"
    viewBox="0 0 18.178 18.508"
  >
    <g id="ic_chat" transform="translate(-3.266 0.1)">
      <g id="Group_445" data-name="Group 445" transform="translate(3.366)">
        <g id="Group_444" data-name="Group 444" transform="translate(0)">
          <path
            id="Path_652"
            data-name="Path 652"
            d="M12.4,0s-.015,0-.029,0A8.981,8.981,0,0,0,5.323,14.583l-1.31,3.041a.488.488,0,0,0,.255.643.5.5,0,0,0,.28.034l4.8-.844a8.654,8.654,0,0,0,2.992.52A8.989,8.989,0,0,0,12.4,0Zm-.049,17A7.874,7.874,0,0,1,9.576,16.5a.47.47,0,0,0-.26-.025l-4.042.706,1.069-2.487a.493.493,0,0,0-.078-.515A7.842,7.842,0,0,1,4.9,11.91,8.009,8.009,0,1,1,20.368,8.947v.029A8.03,8.03,0,0,1,12.348,17Z"
            transform="translate(-3.366)"
            stroke="#000"
            stroke-width="0.2"
          />
        </g>
      </g>
      <g
        id="Group_447"
        data-name="Group 447"
        transform="translate(8.826 7.196)"
      >
        <g id="Group_446" data-name="Group 446">
          <path
            id="Path_653"
            data-name="Path 653"
            d="M119.938,146.7H115.49c-.453,0-.824.221-.824.491s.371.491.824.491h4.448c.453,0,.824-.221.824-.491S120.391,146.7,119.938,146.7Z"
            transform="translate(-114.666 -146.7)"
            stroke="#000"
            stroke-width="0.2"
          />
        </g>
      </g>
      <g
        id="Group_449"
        data-name="Group 449"
        transform="translate(8.826 9.649)"
      >
        <g id="Group_448" data-name="Group 448">
          <path
            id="Path_654"
            data-name="Path 654"
            d="M121.239,196.7h-6.083a.491.491,0,0,0,0,.981h6.083a.491.491,0,1,0,0-.981Z"
            transform="translate(-114.666 -196.7)"
            stroke="#000"
            stroke-width="0.2"
          />
        </g>
      </g>
    </g>
  </svg>
  <svg
    v-else-if="name === 'send'"
    xmlns="http://www.w3.org/2000/svg"
    width="30.831"
    height="29.798"
    viewBox="0 0 40.831 39.798"
  >
    <g id="send" transform="translate(0.418 -5.784)">
      <g id="ic-send" transform="translate(0 6.197)">
        <path
          id="Path_1425"
          data-name="Path 1425"
          d="M39.58,23.861a4.19,4.19,0,0,0-1.945-1.945L6.018,6.617A4.19,4.19,0,0,0,.3,11.946L5.8,25.688.3,39.431a4.191,4.191,0,0,0,5.717,5.332l31.618-15.3A4.191,4.191,0,0,0,39.58,23.861ZM4.8,42.245A1.4,1.4,0,0,1,2.9,40.468L8.248,27.085H36.131ZM8.248,24.291,2.9,10.908A1.4,1.4,0,0,1,4.8,9.135l31.33,15.156Z"
          transform="translate(0 -6.197)"
          fill="#59d470"
          stroke="#fff"
          stroke-width="0.8"
        />
      </g>
    </g>
  </svg>
  <svg
    v-else-if="name === 'calendar-white'"
    xmlns="http://www.w3.org/2000/svg"
    width="40.558"
    height="40.558"
    viewBox="0 0 40.558 40.558"
  >
    <g id="calendar" transform="translate(0.5 0.5)">
      <g id="Group_475" data-name="Group 475">
        <g id="Group_474" data-name="Group 474">
          <circle
            id="Ellipse_22"
            data-name="Ellipse 22"
            cx="1.768"
            cy="1.768"
            r="1.768"
            transform="translate(28.291 14.803)"
            fill="#fff"
            stroke="#3679e0"
            stroke-width="1"
          />
          <path
            id="Path_669"
            data-name="Path 669"
            d="M33.377,3.09H31.368V1.545a1.545,1.545,0,1,0-3.09,0V3.09H21.247V1.545a1.545,1.545,0,0,0-3.09,0V3.09H11.2V1.545a1.545,1.545,0,0,0-3.09,0V3.09H6.181A6.188,6.188,0,0,0,0,9.271V33.377a6.188,6.188,0,0,0,6.181,6.181H18a1.545,1.545,0,1,0,0-3.09H6.181a3.094,3.094,0,0,1-3.09-3.09V9.271a3.094,3.094,0,0,1,3.09-3.09H8.112V7.726a1.545,1.545,0,0,0,3.09,0V6.181h6.953V7.726a1.545,1.545,0,0,0,3.09,0V6.181h7.031V7.726a1.545,1.545,0,0,0,3.09,0V6.181h2.009a3.094,3.094,0,0,1,3.09,3.09v8.808a1.545,1.545,0,0,0,3.09,0V9.271A6.188,6.188,0,0,0,33.377,3.09Z"
            fill="#fff"
            stroke="#3679e0"
            stroke-width="1"
          />
          <path
            id="Path_670"
            data-name="Path 670"
            d="M279.349,270a9.349,9.349,0,1,0,9.349,9.349A9.359,9.359,0,0,0,279.349,270Zm0,15.607a6.258,6.258,0,1,1,6.258-6.258A6.265,6.265,0,0,1,279.349,285.607Z"
            transform="translate(-249.14 -249.14)"
            fill="#fff"
            stroke="#3679e0"
            stroke-width="1"
          />
          <path
            id="Path_671"
            data-name="Path 671"
            d="M374.786,333.168h-.7v-1.622a1.545,1.545,0,1,0-3.09,0v3.168a1.545,1.545,0,0,0,1.545,1.545h2.241a1.545,1.545,0,1,0,0-3.09Z"
            transform="translate(-342.336 -304.504)"
            fill="#fff"
            stroke="#3679e0"
            stroke-width="1"
          />
          <circle
            id="Ellipse_23"
            data-name="Ellipse 23"
            cx="1.768"
            cy="1.768"
            r="1.768"
            transform="translate(21.218 14.803)"
            fill="#fff"
            stroke="#3679e0"
            stroke-width="1"
          />
          <ellipse
            id="Ellipse_24"
            data-name="Ellipse 24"
            cx="1.179"
            cy="1.768"
            rx="1.179"
            ry="1.768"
            transform="translate(15.324 20.697)"
            fill="#fff"
            stroke="#3679e0"
            stroke-width="1"
          />
          <ellipse
            id="Ellipse_25"
            data-name="Ellipse 25"
            cx="1.179"
            cy="1.768"
            rx="1.179"
            ry="1.768"
            transform="translate(8.252 14.803)"
            fill="#fff"
            stroke="#3679e0"
            stroke-width="1"
          />
          <ellipse
            id="Ellipse_26"
            data-name="Ellipse 26"
            cx="1.179"
            cy="1.768"
            rx="1.179"
            ry="1.768"
            transform="translate(8.252 20.697)"
            fill="#fff"
            stroke="#3679e0"
            stroke-width="1"
          />
          <ellipse
            id="Ellipse_27"
            data-name="Ellipse 27"
            cx="1.179"
            cy="1.768"
            rx="1.179"
            ry="1.768"
            transform="translate(8.252 27.77)"
            fill="#fff"
            stroke="#3679e0"
            stroke-width="1"
          />
          <ellipse
            id="Ellipse_28"
            data-name="Ellipse 28"
            cx="1.179"
            cy="1.768"
            rx="1.179"
            ry="1.768"
            transform="translate(15.324 27.77)"
            fill="#fff"
            stroke="#3679e0"
            stroke-width="1"
          />
          <ellipse
            id="Ellipse_29"
            data-name="Ellipse 29"
            cx="1.179"
            cy="1.768"
            rx="1.179"
            ry="1.768"
            transform="translate(15.324 14.803)"
            fill="#fff"
            stroke="#3679e0"
            stroke-width="1"
          />
        </g>
      </g>
    </g>
  </svg>

  <svg
    v-else-if="name === 'vacine'"
    xmlns="http://www.w3.org/2000/svg"
    width="49.14"
    height="49.143"
    viewBox="0 0 49.14 49.143"
  >
    <g id="item" transform="translate(-63.35 -335.463)" opacity="0.97">
      <path
        id="ic-vacina"
        d="M35.016,72.328A1.433,1.433,0,1,0,37.042,70.3l-3.768-3.768L35.2,64.608l2.539,2.539a3.175,3.175,0,0,0,4.486,0L63.44,45.931a3.166,3.166,0,0,0,0-4.486l-.851-.851,1.771-1.77a1.433,1.433,0,0,0,0-2.027l-.676-.676,8.644-8.643A1.433,1.433,0,0,0,70.3,25.451l-8.644,8.643-.676-.676a1.433,1.433,0,0,0-2.027,0l-1.77,1.77-.851-.851a3.137,3.137,0,0,0-2.234-.927h-.006a3.155,3.155,0,0,0-2.24.933L30.638,55.559a3.164,3.164,0,0,0-.007,4.479l2.542,2.542-1.926,1.925-3.768-3.768a1.433,1.433,0,1,0-2.027,2.027Zm24.952-35.87,1.351,1.352-.757.757-1.351-1.352ZM32.657,58.012a.3.3,0,0,1,.007-.426L53.88,36.371a.3.3,0,0,1,.218-.093h0a.288.288,0,0,1,.207.086l7.108,7.108a.3.3,0,0,1,0,.432h0l-1.7,1.7-1.532-1.533A1.433,1.433,0,0,0,56.152,46.1l1.532,1.533-1.919,1.919L54.233,48.02a1.433,1.433,0,1,0-2.027,2.027l1.533,1.532L51.819,53.5l-2.992-2.992A1.433,1.433,0,0,0,46.8,52.533l2.992,2.992-1.919,1.919-1.533-1.533a1.433,1.433,0,0,0-2.027,2.027l1.533,1.533L43.928,61.39,42.4,59.857a1.433,1.433,0,0,0-2.027,2.027L41.9,63.417l-1.7,1.7a.3.3,0,0,1-.433,0l-3.551-3.55v0h0Z"
        transform="translate(38.968 311.082)"
        stroke="transpherent"
        stroke-width="0"
      />
    </g>
  </svg>
  <svg
    v-else-if="name === 'map'"
    xmlns="http://www.w3.org/2000/svg"
    width="20.632"
    height="29.344"
    viewBox="0 0 20.632 29.344"
  >
    <g id="ic-localizacao" transform="translate(-76)">
      <g id="Group_560" data-name="Group 560" transform="translate(76)">
        <g id="Group_559" data-name="Group 559" transform="translate(0)">
          <path
            id="Path_1423"
            data-name="Path 1423"
            d="M86.316,0A10.318,10.318,0,0,0,77.54,15.741l8.189,13.2a.86.86,0,0,0,.73.406h.007a.86.86,0,0,0,.731-.418L95.177,15.6A10.318,10.318,0,0,0,86.316,0ZM93.7,14.719,86.446,26.833,79,14.835a8.6,8.6,0,1,1,14.7-.116Z"
            transform="translate(-76)"
          />
        </g>
      </g>
      <g
        id="Group_562"
        data-name="Group 562"
        transform="translate(81.158 5.158)"
      >
        <g id="Group_561" data-name="Group 561" transform="translate(0)">
          <path
            id="Path_1424"
            data-name="Path 1424"
            d="M171.158,90a5.158,5.158,0,1,0,5.158,5.158A5.164,5.164,0,0,0,171.158,90Zm0,8.608a3.45,3.45,0,1,1,3.444-3.45A3.453,3.453,0,0,1,171.158,98.608Z"
            transform="translate(-166 -90)"
          />
        </g>
      </g>
    </g>
  </svg>
</template>
<script>
export default {
  props: {
    name: String,
  },
}
</script>
