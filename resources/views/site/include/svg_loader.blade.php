<svg xmlns="http://www.w3.org/2000/svg" version="1.1" style="display: none">
    <defs>
        <filter id="goo">
            <fegaussianblur in="SourceGraphic" stddeviation="15" result="blur"></fegaussianblur>
            <fecolormatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 30 -10" result="goo"></fecolormatrix>
            <feblend in="SourceGraphic" in2="goo"></feblend>
        </filter>
    </defs>
</svg>
