!function () {
    "use strict";
    var t = window.wp.element, e = window.wp.htmlEntities, a = window.wp.i18n, n = window.wc.wcBlocksRegistry, i = window.wc.wcSettings;
    const b = [
        'casso_up_acb_data',
        'casso_up_agribank_data',
        'casso_up_bidv_data',
        'casso_up_mbbank_data',
        'casso_up_ocb_data',
        'casso_up_sacombank_data',
        'casso_up_techcombank_data',
        'casso_up_timoplus_data',
        'casso_up_tpbank_data',
        'casso_up_vietcombank_data',
        'casso_up_vietinbank_data',
        'casso_up_vpbank_data'
    ];
    b.every(ele => {
        const ps = (0, i.getSetting)(ele, null);
        if (!ps) return true;
        ps.forEach(p => {
            var o;
            const title = () => (0, e.decodeEntities)(p?.title || "");
            const descriptionBeforeBankName = () => (0, e.decodeEntities)(p?.description_before_bank_name || "");
            const descriptionAfterBankName = () => (0, e.decodeEntities)(p?.description_after_bank_name || "");
            const powerBy = () => (0, e.decodeEntities)(p?.power_by || "");
            const bankName = p.bank_name || "";
            const name = p?.name || "";
            (0, n.registerPaymentMethod)
                ({
                    name,
                    label: (0, t.createElement)('div', {},
                        (0, t.createElement)(title, null),
                        (0, t.createElement)("img", { src: p?.logo_url, alt: p?.title, style: { marginLeft: "10px" } })
                    ),
                    ariaLabel: (0, a.__)("Casso", "woocommerce-gateway-casso"), canMakePayment: () => !0,
                    content: (0, t.createElement)('div', {},
                        (0, t.createElement)(descriptionBeforeBankName, null),
                        (0, t.createElement)('b', {}, bankName),
                        (0, t.createElement)(descriptionAfterBankName, null),
                        (0, t.createElement)('div', { class: "power_by", style: { marginTop: "10px" } },
                            (0, t.createElement)(powerBy, null)
                        )
                    ),
                    edit: (0, t.createElement)('div', {},
                        (0, t.createElement)(descriptionBeforeBankName, null),
                        (0, t.createElement)('b', {}, bankName),
                        (0, t.createElement)(descriptionAfterBankName, null),
                        (0, t.createElement)('div', { class: "power_by", style: { marginTop: "10px" } },
                            (0, t.createElement)(powerBy, null)
                        )
                    ),
                    supports: { features: null !== (o = p?.supports) && void 0 !== o ? o : [] }
                });
        });
        return false;
    });
}();