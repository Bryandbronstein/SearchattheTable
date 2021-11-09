const fs = require('fs');
const readline = require('readline');
const {google} = require('googleapis');
const Cron = require('cron').CronJob;
const winston = require('winston');

const logger = winston.createLogger({
    level: 'info',
    format: winston.format.json(),
    defaultMeta: { service: 'user-service' },
    transports: [
        new winston.transports.File({ filename: './logs/downloads.log' }),
    ],
});
const SCOPES = ['https://www.googleapis.com/auth/drive'];
const TOKEN_PATH = 'token.json';

fs.readFile('credentials.json', (err, content) => {
    if (err) return console.log('Error loading client secret file:', err);
    authorize(JSON.parse(content), getTranscripts);
});

function authorize(credentials, callback) {
    const {client_secret, client_id, redirect_uris} = credentials.installed;
    const oAuth2Client = new google.auth.OAuth2(
        client_id, client_secret, redirect_uris[0]);

    fs.readFile(TOKEN_PATH, (err, token) => {
        if (err) return getAccessToken(oAuth2Client, callback);
        oAuth2Client.setCredentials(JSON.parse(token));
        callback(oAuth2Client);
    });
}

function getAccessToken(oAuth2Client, callback) {
    const authUrl = oAuth2Client.generateAuthUrl({
        access_type: 'offline',
        scope: SCOPES,
    });
    console.log('Authorize this app by visiting this url:', authUrl);
    const rl = readline.createInterface({
        input: process.stdin,
        output: process.stdout,
    });
    rl.question('Enter the code from that page here: ', (code) => {
        rl.close();
        oAuth2Client.getToken(code, (err, token) => {
            if (err) return console.error('Error retrieving access token', err);
            oAuth2Client.setCredentials(token);
            // Store the token to disk for later program executions
            fs.writeFile(TOKEN_PATH, JSON.stringify(token), (err) => {
                if (err) return console.error(err);
                console.log('Token stored to', TOKEN_PATH);
            });
            callback(oAuth2Client);
        });
    });
}

const transcripts = [
    {
        season_name: "aih",
        IDs: [
            "1xCBFsZqxr56ELVzXcpZ5nmH8bQO-rUHE66dbUEWgnA4",
            "1sGQ3G9hATV_mBzrWLKPzFX2sZgdrGwIrleNBmUoC2O8",
            "1tKREOydDofdyNWsEiAYGa6Oe4rdgIXvbq57f-BQoJtk",
            "1_Lbd0qtfApJPfYVWj9e9mtn1yRUbOwrkyspZi-49MPM",
            "1bQBl-pe7V3KXu_oY4FKJatFGEuKqjHzbzGBPkAewd2c",
            "1XdbXmjv2injl8Ua7_KtpaBRzIvCsv23w269KvvV4rJs",
            "112IWBUU1Yes1A7u75xKSxvGjFtcktUzW0weW0uLPvN4",
            "1Sw-f33rrfarmd1AX8Gmd8bENbnmdItVBCTKIqimV_Uk", // Autumn in Hieron 07: Boat Party?
            "1Qe-Za5Yq_60X2-xIINdzP3EAVXxicwAR4zzK991YhgQ",
            "1znte6qTjshc1vFiF7UE9H3tk1FwEcTjBsLuWWWkL0yU",
            "1B59UxpObG0U_ky8o5YbyRC4RC2xS-a9hbzJBB-DT_Kk",
            "1BHXPnZty5CHuZAQwiB2019m_doof7ADB3wksa_NWzNo",
            "1OSPBC5R-8M0KoAmJfjEQS86wqdxCWQWCSGRlLklbjQI",
            "1IpnFHplPXGH2IOEpTs9g2w-yLGTEkpH2VQuawpUUK4o",
            "1Kz3rzwRRpSdMoIkXAIWI3hmdgOuiwUql5U_ZlBka2tE", // Autumn in Hieron: Holiday Special 01: I Don’t Know What’s in That Box
            "1B_GSuQ42l0Y2kcxiGl0gC1cgcKekDmV4DbmGsaQOV0w",
            "1xyHZAhhv1uaJONTXVs9r9sWvYNCCCxWv6y31zMax0wY",
            "1Zxf5R4YiPCyHkGneCSxnTA402GfJBfEM6I9_S3RFDD0",
            "1cnLCI-g5NAqt6Dd-IaHcNOraiKRujeZvOJY9GRqpKVI",
            "1vptygnyKDEPUnICnnpCTpYOpZxfbPebn-st23oZ2Yro",
            "1WqnCE4BlWsiWBXR4z9SX6B7Q_StMaXbh6dAlPYPqdRg",
            "10fwGxLGO0KBnB9b5p0nyqvp2YB4csJB7JFNltMOoQj0",
            "1uEEyEY0CcWKuo0pWSSXcRpbJUyO4haeiate3zYdeW4Y",
            "1Q0-VP3_DdZzQ7xE9LmyK5kLi1Vqy6MTLOerdWtGwzxM",
            "1F0BUHPkeX1ShpOWzxl_xr2BqbjlD4xsmHDwZLRQ_i1w",
            "1xk-tRqgsn65kTy6gNplng6qYJKgNuGuLZ4BAP41c-1k",
            "1ND8ah8CTIopM97F7UsMukMD_fMdtdEuBFFCM0rL6L4s",
            "1HFKCMM7f74dP30FNbzBq0ZkrvX2A7cLxYwLFmXNGeDE",
            "1TTy0x1iYdvYoeaolRe1a_yMqxUfO7PCt9yxC925p8TE",
            "1N77h8Sd1wE5ZNjVxsBlE11AcqWGuaKsh_V0MxDfmqKk",
            "1neSxYVTSu6T07ZtaGkoJXgBc8qCnd3XLThkuWopwehk",
            "1Nb73-BDfUzxYbNIDiVKGe6W-GvZNxFCfI7jHDNGtJbk",
        ]
    },
    {
        season_name: "mar",
        IDs: [
            "1YNrFWHqMYMbpoPgXNusPhCQ7pw4q7-K6jDjGE4wt0Qo",
            "1b_Wwzcd7orjAa_KkCgtHcdVJYEieQtsX6n1kq-TgW4g",
            "1_QJyConmw9eMg3ZmRe5MexHev_vIIB7K4hqTaIv_FYQ",
            "1-0h-w9oQkZZWXiAtC5TYD-9grZsXyI4rv6VFKqUKgzY",
            "1PUocibul92TqMnoFeia5n1gVF9YNyN6QDPVsICskTho",
            "1UFSICn5iLXzO9EEfyWXptpsiYpPM2BYsOs8So-CbLlo",
            "1eMyiu3G9MCwJRfvRSIsV1KrZEfsMS6Cc53kUzr4pDmw",
            "1rytp_f9d6JWy0z5W-HRFy1IijVWALrt6c7h4czCpZLw",
            "1Pt-wDU0wuksgrvTgQLMXOlI11KidU11ebi5oAZ0CF08",
            "1FZbTPPG_vD6f7eUTAC2jH-IoLBdbB8nneyUuzv_AZzs",
            "1kwOiYc1-bFyOXQHgHFzyJnF4mTOcZfUnMVeADTCW_8Q",
            "1TzLo8-waXbBWK4okB1KaIV5XqgrCejN95gcaZf16nnA",
            "1Q-rOAsZutI01ERj5TfWUG56XNP_V8rPkuokkdgr8vrY",
            "1vzWYoApFwjl4Kqahgo5uTpEdNanF23vX5Gf31uCneVo",
        ]
    },
    {
        season_name: "wih",
        IDs: [
            "1TtgXIZ1xzrgV0m5BNbWgVWysfn36dJJPyniDalWhBm4",
            "1_V5lKB6cazOIXf08ZoevybaeZXLhTWTiWyqx-4FAQ00",
            "14-LucHUuVd1NU7XbjZnke4nnU-ON-2UmXjVH3Riem6o",
            "1EZKv57hcZmgUrW1IvhikuCdDbLe1WCrsMPYW5lJVfw0",
            "1kTNV38eb1-Karz7FW7F9SaJlK6_tpjOLSTThC_lcNs0",
            "1avSyyze0bkLQ-ZY237fkdG362rjo0WUghVl2h0ieP_U",
            "1_snTsq37Yaosyi0PieAAYUnISXinW25bH0u7t2hyAgs",
            "1ggW6dhmbCUlWTjocnZXeJaN0bMoywAzA3Tc0tCy_-5s",
            "1VbyARuQZXK36bnvCbRAz7FyQzud7mEWE07V21PRsAno",
            "1Vww82yf6ED_8I1pzBqIo7IZ_ZkRqo9PX8fSBg-QIzzk",
            "1QY2vCWmd35MKIHYHNtdra5UtUW_I3nY-bzxCvrrA1Bw",
            "1HZt3DRmWU3sXZkglv7NhH43wGqEALiBxbhTbC2mMBiA",
            "1IhCWjP8OcBizCujRFYeSkvarSCWrCyTq2-1h3scMh_A",
            "17sojv27pAp-BmMF7aZwZH1wuK5FCgAlOrTu_wKlN63g",
            "1HyFk-6dmfanuISIi3Y-oVAcUjsaq7S372SDYsgwWM24",
            "1cu5PkmzZzzGRl9-lNPW5BcHN1NIX6u4isswZ2-Odzto",
            "1XCV2D_fovTWog88_its_wufIwmP-GFudsI2vHQ720SM",
            "1pfPffnk6a389u78zA3cuq0-EexlK_4N5wGpnHlug7K8",
            "1eGGx5itbO2K6MypL_IKggH3exg86qQSS_jE_p_O2aBQ",
            "1aiAcR51Wq4visvVyV9Pz0FytlFfRnkKbdMSAxNXXB88",
            "1umKwazFzzIHzNulxmjT1cc2f_IiDBFemAo_yej45HyQ",
            "1ANCGerxiSn6v4k1T0PLg3cwNuMPjcf5AHDLu-5XcSzI",
            "1iB-SBGuZ5wCVC5iEG--BFMCIZO5hQR1V7l3bhkoRCB8",
            "1rCJ8OQ1atSO23_iEOvU1R2j9YAkVT41CodJpqMMlc-g",
            "1uIObGIs8zbaMIy8kYmjuHMLHZpqua3XxZu1A2mwY7bE",
            "1W1M9p_c-lcvtgg8TeKiIJUMez1UepP5yXZiz_l3Zyrg",
            "1zYTlyZUxnQZv2s6Obdnn92_JXyoKzdJIes9MpYAC9Kk",
            "1RBXGpN2VOEXx-gKN7Vf6--zATRkhkQ6pkIRBX1LNaRY",
            "1BY6FWPqBamQh4SozBm8HnOOpMjyQs4gUnu-qvWVjXpE",
            "1LNMAGsThH5QPFZWBq4zMjyN3OY41zIzhONTfpvPjrIY",
            "1zIF4KGGJnMAffG7LQygtv9c_H08zsN8d3-CDXLPyh-s",
            "1mExDKgviCbBJoZFnuV06NR5PouFjQp_3cPKlLRMp2OM",
            "1wBmC8Gg4pzpaqaHvj03WlrsRDovlqGxMtv2fqru2iOY",
            "1ih9POVwqoRqpWKSNBpAnGvQh7wjP9fKfgW-OYtKkMU0",
            "10a5kH5BbSFsxldgGK6YPT_-21H5-UUouBMNlHPXjUGU",
        ]
    },
    {
        season_name: "sih",
        IDs: [
            "1pXhWrP6qwXQZrnW5xfD6Ezbow0CwWyCWJhc9HvyJVs4",
            "1EI-OSedAI_Wx_EhB_o1v1D74nOMmj8gIBS5E1crsZw8",
            "1HOeumDaoT9vvoV-DdBgMLzkR_bEKIZ8M6MOvMdXOKqI",
            "1namfl7xM8lzbiy3hLCsFYAdJi99IU3FR37EITckiU2o",
            "1xT1NS5muS_CidOkujUrfmCn-paH7LNcnOxcDmBdbpyg",
            "1WDpybyvLsxFXyNBM4DeM-ykDnMQpsRP5Mj1JnlY47Sw",
            "1gl_rVHLTNCtI3NTYTXaz4rGaiyMjUwhC7SddKugYEb4",
            "1OANnZUVctQW-s1kJTRDOt_Qev1MH9kfzvMK3bAO-3cE",
            "1HzAfV16tjZUhIY8ECen6dN4zqN0ERr7I1LKr0BeEm20",
            "1UxfGwixFW3FmiYyKS6j7zjFCg_-U23Jqvbd3qT9xQpQ",
            "1BkbjyGTy-Jbi2mXd9HhQxokRTR0Rfctcjsmpi0I2SRE",
            "1rgkwGiec8a0PkSw_qv70p7hO3p4OuqEmm94SD8yEYSs",
            "1n_rshZHuQP1OOmLFzRDM_LYZJpERZAJK-LjiNTp0SL0",
            "1T78q6ts3JMzWXhgvwQX64Ur4Qsem0lSJ8mRq7BJfzes",
            "1IUTdPwgHt7X8Dgt7cXm-FfmUw9ahQSh2dnndWHvKnAU",
            "1SiCMLrFkUrvvIg2E-dYHlqWM-Y5WrrN_VqI-1FgtB2M",
            "19Wce01rn6N-ExssK4Msyr4U-ufz4Lf2yEygfbUUo11M",
            "1ODhBOiFobL2FFEEPsECa-eCSzLxEzDWc3HjyAyxn7Tc",
            "1qw9flqwCq3vNrWINAxHdxg1MIMSQXp5HdOTb_8Z2X9w",
            "1-HCJ5QXVxM5c4hdNhumlKyBoz3DLOetkxE-GF_kqNnY",
            "1mx3CS_j4btGmd5zAKmUMldy_wP_u_nZfcLewFrmNMuM",
            "19KQ_j0MJxAizgSq5TRh2bLVB1XtTzy7BwkYY2MV7q_w",
            "1nUz4FlOgZjpwjcvD4hj0DhrdxcQw4cHyh_xENvq6wb0",
            "1MIMPHvOQ7FsjVklKYReGrXhupjN6iqLkV6Cebu4SxyA",
            "16TrUI85s47EmzloIMSwPSJbXfkMAlfnm8vChoragZHg",
            "1Tv_MrggBEPNhpvdB5ZlCehOO3mzGndYW-bfK8NyNXxQ",
            "17q7n6-rml-uy56Sa-OQY5f5b7vd1A6U3n1LAkBytcMQ",
            "1-cSlK86PI1mZa1Qhg8hLZHrP51SQRKGww-JbkpmZ5-I",
            "1m8OUgoZZUb4VE0UWLqoia8EK4Rk3fA4IAxBPWu_SK6s",
            "1YKUeYIfq9y2Hff-pA6xaIJgEIFVdKK_Ef1SNy4iKsYM",
            "1NbxdoISnHU1AQoB0_cE_asn8CI0052iaPQqRgLQZJes",
            "1-0XA9iGyLB4kkK1yFJddNJa9u_epIeMevDRKVzK6tA8",
            "1NaGkSrNtzR6N6SuslS-qAzjuVHRQtezzUs0Q6PPbVfc",
            "1e8zMfsxKtX8fsB9z5tqA9i5ErEDAFj7g_Wa3m-ek_vk",
            "1naa-51CZBb3rsm-v7lv3Mg5-mwTjbkoY0518qSjbAmI",
            "1A0PtUFqQNUNEFfJsdAflgmQiKn7q_xSrwn8VTD_NVLo",
            "1KI_Ab3z-O9CYAXswp-UCq1J-tcE-VjcnTPvTklh0VF4",
            "1K0nlDDBBr4DZDicKScOKHzNSk45k5WgKLWmbKVlvLJg",
            "1SS7fu1zohnVvvjuCMYPxq0MVPGzXRCrKVZRap48GsMU",
            "1BsVHGG4A-_V2m-581OYquMCF4yYM_-Bu-zYtOE5XWuQ",
            "1b9P2222Wqjmz5x5-eaas7_8o8CTTsaNBDGs81ej3Ry0",
            "1C2YmtH7UD3dUF7RcrlL7nzVoxFHWhMVdsyUdL6wi9pU",
            "1RG9aaeqy_uSrbaVg3yZRRKzb9C1fyIc29EOA3b7New4",
            "1fZL9kTLrAw0CfU-MUdU2R7a94aRYyD4tdKTmzjsN07g",
            "1j_MDOpnXERKXz30rYTlGGplmMbaNYfEQB9nVzO5d2aQ",
            "1BDV4sAYh5NZSAW5d19p37VIWiD2JEB84kpIAvWkKX8s",
            "1j7oLy5JkvxGOSzoMGJd6GW4xZo7vBSShQ3ij8QikFmk",
            "1_eWP94AXFwIKEsCXRV5I3j085lwFkPhQ-0-DwqGpJLw",
        ]
    },
    {
        season_name: "cw",
        IDs: [
            "1zGovC79LsvuwVViOvqTCtz3SbRGHfZETRwkFm1WFFqg",
            "1dhlsagqfVSudwUyokFRNvQWHkUOJIUC2D4sXBNDCNSI",
            "1mh8uLkw9dOkfiiMTvMUOilV-1Q9cC5OofAp_galM_RE",
            "12ZzLK1LcKfgRwGVM83hujIXtpGFqhjhYIqOyZiVESDk",
            "1RAHszTNZAxrr5mHH7rsZ-8ZcmwZYLOOTpEi9eWbdz0g",
            "1frsrs_DN4bQ8a6r3ew4EjyVELCKCYTPnxPlcKHc0acU",
            // COUNTER/Weight 05: A Ship of Seeds
            "19CSVitxMRtKKXr8m4swuczgAHtwcTT6sEEZaxSzQpgI",
            "1lhNyIPWe3YfGL_YYdcHB6ezKj9tL7uoW4-2njNAdPYc",
            "1nw1FxaxTJ40a8hqqU-nTy_auC3zehaVjvCWvAh4AwYg",
            "1dqfYFgO5lDmED7kvm-wQe9ejblNx6J7aCJTqv-tW4A4",
            "1wtmzUIBlFn5oKtAQcnaBNgrHOSEuu9i64L4-C5Wj2yk",
            "1_zHGRtTLKXahrdEIlcCEFGddIEkrWp0LcGIRvV5yFDw",
            "1Kxs2MC2ygjiOKTGx-BKYH3ykFxxXWOyzSXTnkhnYudo",
            "1axrkgj-V9GA-2kpKrGAm8kcHP5YZCOFX8WiCY_IBzss",
            "174wMIQjE6vkDy6y8zieQdfuUBajluVBh3LNdFga2QvQ",
            "1MPTGfKDr1rGpimeYBcNv7WRp2h9AvqWYbZRxlsL0r2Y",
            "1qxHa2TFjR_xZkANDVNwXsDXwpXzsIOqtNt6lWiwWqH8",
            "1yzOKk9rbScejA1Pb2Zju0JpHSAL8RmbWStB4bs3LFxg",
            "18BBHEX5HhxxlwT-QZ_gvkcYIKHyqo7TbgyeJqkWRP7k",
            "1wwdORMRDDGNvA_xUfMZ5IMGJsS_sGeoLYPf8ggfYb3s",
            "1Cur7KA7HA57LY58mcZYNmFc4wUU2U8OrM8jnLKJJzXQ",
            "1tvjEiwmI2thMAtfiBIQ3KYydJU4wyVUc9eUxSXhTTqo",
            "1IgqusuLppdv7YWl4VOHCq3a0IW8ePEu5EGzbS133Zhs",
            "1xYk9E3HNzL3YhuXxojsPMw5Or8ReDrSsDw-5mq4mltw",
            "1Rhs25IuZM9WQkNmkQrzkpVBLQ9dR9Zq4TIwvjwBCNpw",
            "1TLQf8wVYi8brq5wvzaHfAzp3crTyFA5SjW6FHwMX18M",
            "1X3F4zcYL_loYDu5vLFdZbdg70uX-tgc3YAcjvzv7-r4",
            // COUNTER/Weight 27: An Animal Out of Context
            "14vys-zQqtpjrrmgXPufHtknmqGM-ejM59eoEsA_vN0k",
            // COUNTER/Weight 29: Three Conversations
            "16SlanKUNyiVILsuNb9AuFweXTiehrz4wIH-JPNrirOQ",
            "1G3esJnyHRLwCPO7ZLAR2DHR5LzTRN5YU-R_fL0dml3c",
            "1jAkxN2wUXP2O50bvXo3nhA_aBhiwVMXn49T9LwouFjQ",
            "1ZZ6l8epOeigeGnNw_ORFEOrZb-GFHFjqWLA54CXqazU",
            "1wkGPXdSbyXVL5syIKq9NjnQ6secDbiskzmh8lMHv4QY",
            "1azQF-B4O-hHvzpp-6_QE-8UuTRuOlGbIP8dz5p3Bwyg",
            "1iyZCsvyeefkjY6rsnx5oYQhpk3DZ44iuUq7ct3JC6J0",
            "1ZIhr8_alkz_SfmHovIVWt1e3Izqf5UOfikjCFkN-UC0",
            "1FDXf11B7W3wigDZeYgxpXR_-N1elCAdqt3K26n0Y3D4",
            "159OscYtwecH661AxBD6kpERKJgyqFio1rUlJCrDBmH0",
            "1BiqYG9bkyaM5aVdBoE6VupR8rq0hkxUAjq9-VUWiGFc",
            "1Ij9K7s54RnIy7H2NSzb2RSUAvo6VWYTLUfiiWFgHYfE",
            "1fe1wP5Ik-Ds9tdnhrFC7J8SgBHIlQFPwmkN0jrc2Z1g",
            "1R6BoFUKEYVF_zqOa06TNoSKOLuTAOASCP-9yVv1kT5I",
            "1jM31VkB31Fw84t0LcfQtwSr_FmFFGhPYlisZ7tYm2NM",
            "1nsU5-aXmfp_heVcOFZ0CodbaGwB14FaMAHyaH6gIGkA",
        ]
    },
    {
        season_name: "tm",
        IDs: [
            "1sUis9SBhgH6ALDkruEFx_jNg5RvZ4Sk3fMSpBaf6DMw",
            "1wMbqJgBTkq1iCJsojt4mhi53vgnkd-h8utfz42F9RDE",
            "1xheU5Dfpx0Jep_E98yRN2AE3W2cT9Nb1Gcc3CmnA-IE",
            // Twilight Mirage 03: The Planet of Quire
            "1QgUDzt2DEnQ7RknfbZasjdtMFucFNLJc9LvD6NO7b_k",
            "1ZXY8ppz03W0zlo6sAZP7za-yO8Hys6UJ-cGm8TNUuGo",
            "1Ec9DpAOXCbfNM6pxCbYV-RxUH7e8RjxIJ--J0WJZ9hs",
            "1QeA5wmSE1ADw_kGPwWDe9c8_9mOmShymCpSqO4xoFkc",
            "1HzWQB1vZuXpEv87mXUeFLCScqs2uU330P8C1wqNM3hw",
            "1XWBJHqCYqnCZAak7_O5qPWKP_BxaVdjvs-SH1B8FVt0",
            "1vW25xDUrFWBVWw1_Mt2aX8Kdm0MbYf3ezcVTC80EeE4",
            "1xkQHDb8Dfaafr9rpmjA7aiiNTKDbaxrPgQOUhzROmGM",
            "1FjBp_WmFXLHLKdiiKcJ01U1ZASbxtTGhQdO6la-A7ME",
            "1r26GNDkvZOqO8-O4lFcX1WV3WPAG-c7Gij-y4p_fDgY",
            "1dWYhP7maRexQDS8kcA_JbFzMy-E6oRXGawiCwh4M63U",
            "1C6LJmoKS5FroMy2C9u7WTU6NpxTnJ-dKpmT1QrVLTLE",
            "1_uK0HyqKx0rQJsRoDtWOKm6favwdg9h8fn9-qRATZlw",
            "1pc2QQX6QUep70rhsuNg45n_-xOfLCr-QYjutpocZ6n8",
            "11K_pirBG5hxnlegizPSq9Sj-PkarfVMATe5FLoqLc1A",
            "1fjdzcgOoyZ0WrvjThJ_KPnXrXZ5Fp4-U1xbVLFZQWh8",
            "1-xgJnGuw0mTozrIJDUkIoxP0Jpg1uyvJQIjnAc2DdMY",
            "11rMeHwvC6YTzKNjvcFCl6pBICt4G9EI9c3bdmOxPbTc",
            "1muwtbtGzAsGTlMWcnGJeUB9ucaPRYjjmByJ4vwmJ9oQ",
            "1wU_sQ0DY8StcyGJykN9LsJ0pAcfeHIx_1c8c6r5vGp8",
            "1dTZNobzzspfajnJV32P00wcwtMjDiYl83x5l9t71x9Q",
            "1NUdc9oHDttp8XNIGvGUdWDk8kHPzfMDYBqWjnQdT-bE",
            // Twilight Mirage 26: The Miracle of the Mirage: The Heist
            "1EBnNWYGS0mBluIvhTguvxjGk8DIgp-9FxMIme1YRgV4",
            "1lTmS4TdtfEeoN1wPlDmz9JfnAXz9E6J6_Qp3L-VdQxk",
            "1rmrBcI70ZO0yuLi_6Rf5-eph8eEa2iICxcl4G85NrAA",
            "1x6RuF1UG5l3wPs2lQDTIXs30ysIE7w2G2q0pviVlqII",
            "1Ruii5niyvSzlkWqvMLYPFp3_SKDnDuAiSEG2dG8H8GY",
            "1WVpr7fNLhJeAhvrpbTwMAkyUQMFSBO5EjxnSrbC2EfY",
            "11qZTwYvzPOwspWHutp4JUFMfzUjeCqKMHilwTrUd5ig",
            "1DievgnHN1OujJN97CcmuyG0Vm45sMDF9yum2C1nWKg8",
            "102V1dLuBS5xToyRnZNeMpU7j7Eth4PFYV-8a7iRP5ec",
            "1V3hm5PNQ7fMfx97jKdknmcA43clTRn0vi5fgGIJ4yVg",
            "1Ypfpyjn_q4SvgmDslE0qDgKqWxKccYtZ0Gn3ArS3ZmE",
            "15AR_aE5HvnQg7GOcyCnpc74lg_Qe_2eXvCINtRLlisQ",
            "126-HIvguGKXAAe-4xjcMYhtXMSNXRQ9-oE3fx2valvM",
            "1EMXqJ9OjF4PnpVdpdJFG4HL-fE1q0GAA8qfa3Xf6pwA",
            "1u2yE26gTUTfOSe5z3__ixaKZVaoWNay2vShPsm-Pma8",
            "1ojeWmu2G4vcosYt6ogMc37L4FD4lJWmV9HVkVF_HPss",
            "15XJs4CnwDeYeA81DQhREkHIbESomTD78K7lg2JHbz00",
            "14FJSegQmxxJwk-IsaWtmftqSLVRoyNTCN5cFe7m31VI",
            "13eAcu_jCDdzBbOHigCg_zpnwmjSDJqlL5l3ePSQeSVs",
            "1ehRz-hnFXLKhzHkoQjDjenDR-8q_W9imcXHeXpg93e4",
            "1o1f5M75c47NCZEdVxUSvNGtCX3HHBJiuvjfYPgVJm14",
            "1bKXr1xq0v0lqhDpZrcDC3HMkkeMYB7ld_ZENMfjZJDQ",
            "13pJjzr4N-lEEF4MMU3tptv2ptIOeZUqTKzZ5SSyYtA4",
            "1c-74rr13dxi0NkwSakhgcVwkqx90Jk5Yn7LWbyOtp0g",
            "14LkFDs1O4qC7W_isx9TRSOA9LZVBtnsUg3LpHoc6pak",
            "1V2VwYw9np5bfYjHCoLMi2kj3-f4-Ds6h15-ZEDdt6gE",
            "130xmSpDZGZWaA3PADhs6Y9tZjrDlQHfx9uLgavw2-1o",
            "1vwkjib7IghpPvQiLt-SHsatZwKX_Owptwje5PLNZ7hA",
            "1jomv54LIQ2D75D-R7cseONE8NR8ShZp1BQhCRL5OTEE",
            "1K4LAEvIoTeiwvs8XyAKazbAOqa341kNsmu34tcUT4tc",
            "1M22mtskeY7e2Y1i2C-vphy3tmiOunO6RmxGHmGwNJco",
            "1KJb-6ZxWvXFzCjHFt1VBXWODD_heNPw7vbstlBMA8N0",
            "1GZ1Dss0Wz7xBMKlEG5IC7LPhJFcGj6KRIJ82WQiLBco",
            "12n2sQb_xn5NyRo0tMKjyYpj7SiEpuQoONtxZWWm61Q8",
            "1oirhNpv3yZIOS0EX4FIDHsVOi2Xw_l0UvNJGWDp1sz8",
            "1uLQDhnxA3RGaymJ2wuiRpEEsd2GkUtQWETKCP_plD9U",
            "1trrjDujyI1ObqHJwPIU4a2oAjbx4j7i3cSPTeOf-QQc",
            "1yO2efCJUhJaOpuPnx2j2Z67c5fMwMAkFJot_8OWAaHA",
            "1ljy5LhbCaLsI9JyW2V0v8UwY4ids5F_tQMOHL6eMS2E",
            "1ssy8Sgq1DMEawThkuaawmhg7n16LpWAfp4lSHllYMYM",
            "1g3YDsQe-cNcEB9MgX9dzBpM2E-BtqPi2Bh_3tGg48Z4",
            "16PrXvOObbm6D-buKCXpaux5kqQa4DDNvssxgmxhyesc",
        ]
    },
    {
        season_name: "ropa",
        IDs: [
            "1RgxhM4DIP_bErPmYgewPGIJs5vQMrm2UU4nONRure3M",
            "1s76j7aaS3-OaDLxXLJGKgaL-ctm-nRek9qfeFqpdQM4",
            "1pMB-gf6pAa-rakvBRr9O-ZC6p1C9fl2p--AMZE1hJRI",
            "18B3_cCnyt4kQIcCAO4R5aBGYzTjPpq1Poe5FB0CLZeo",
            // The Road to PARTIZAN 04: Armour Astir Pt.2
            "1xGVfQqSd7Quo-8lf_8GIjxouV2Vux2PNCwQTC3gnDcU",
            "1Cxg3k5nIAtnYZTm3kgK4nzDQR_X3ha9x9p_Rd5-QH70",
            "1SN7aMDHFL7jhlyMLZ6X8vubxx8SZ3rBqbCM0fbk9MAI",
            "1JD8qCHvGJYdbZSO66wobXQ3Mb8qB7YdFqZG212WCOVI",
            "1fy_FTG4_UJRNg-IvDywON9RpF1zPZZuGIJrHAOCWZzA",
            "1Gl_dpTrkOLu6UwSiOdPyup4t2ymJx6cbI7eC0PbRt8c",
            "18VSJvOkKltxn4aYhTOwFze7WICkpYXiS5sZBXyQv17o",
        ]
    },
    {
        season_name: "pzn",
        IDs: [
            "1yA0X_obPt17vLqbixQLeLdh3QbDhJXYgeTtnP3hOtk4",
            "1xw9iP44H6v4xiYxS1Uu-vgELyGHutTolvcKeJ8JqAX4",
            "1k2SpO2cR4hQHQ5MpzNYLeFokS_x6ROYAMjaOvO1tjaQ",
            "1HKC6juZ_PmbRU1otR5Ysho7S8equ9PwIkO0JnxEoX1c",
            "10CjMnZsZHwrZd5q_ZL-R5U6nok3XO3RyA8FTVbpRjh0",
            "1RMpe1d0RfhhBFZE0IeVtjkWisrrKHDka4ibPiQQjBF0",
            "1gNFIC5aRaYEcrJC7JOm4CyEXjxsJNPJTXg1NcBpO7bM",
            "1lTVi8BlIYZjOAOzHyA0o6UVRb9cfSJBrnS5v7WLBw60",
            "1SnYMmh-o0ltWtoF76IJah9QxEw6CfG6Qqbf64h7ttr8",
            "1Q4bpSMukxzLtQ-6eToE4qe1PnD3wiH87QAA1YqNowQM",
            "1C2FJHip4CiD12IV8Lbs5270LFJ6EJv67qer64vKBgcY",
            "1KyXeGDrX2PuTIObUoFvzFNpuAsJyU3-TjcceAnKc5bE",
            "1UGFHApk1q187VcfcXwbvRhH7_qPoErWa9fSHvtB803E",
            // PARTIZAN 13: A Captive Audience
            "1N1l-0mYQWse9gKoNryExA0osGPIm907ggP1vYwiN3UY",
            "1z3_abjr0BIbNjVFaeqt4Xcql_hjiaORDwoViTlTHwBY",
            "1K90tbt73s4u_lRsXy317zfYyhxMmEBlv401McLNnicY",
            "1KzrvLzEUsi5wd0Bn78m4s0OdVObyfyRtvrNcug4tlbI",
            "1DAdJ6No3TedeM3vF-EO7aH8Ud_VrJvOq0YuxReElonw",
            // PARTIZAN 19: On the Edge of Fracture
            "1LfZw_4IzHzPbtC1xDE-e-8qXAr-2Ic5QhlwAOQ1U1T0",  // PARTIZAN 20: On the Eve of Revolution
            // PARTIZAN 21: Millennium Break: We Will Take Hold of the Loom of History!
            "1uK8RArlWsdSw47VJVYxiDx-mJSMf6zDupiswG1kKweA",
            "1gWOvis33BYNBfSGG7pnkEDvgfCE5htZatvXq4xV86ws",
            // PARTIZAN 24: Millennium Break: The First and Second Arrests of Clementine Kesh
            // PARTIZAN 25: Millennium Break: Courage in the Shadow of Opportunity
            "1VjgLO8wpuK860mP6qpVBkKH12K2IfE-f7fKSnXi5HcI",
            "1M08vgkA9rMcBnuN77B_RaY5Q_VBNWCwwAjVuv-wCVXI",
            "1tqYTK2HJ4ClTeoRHBF9n3SLVwCe5neUO8oHnxBf-B7A",
            "15KtRVAFASW3vo7ip0vl2q3LZCo6T-bv7WPdExAGXMEA",
            // PARTIZAN 29: Millennium Break: Buried Beneath Golden Leaves
            // PARTIZAN 30: A Cemetery for Heroes
            "1MeJhbiGhXkp3S8y643xcEOth-0OaqPuDsKgf-Gtjhoo",
            "1SGWlkenjyFkKzm3V0pmxfNutTHUEyeeQB9dVzycwHkY",
            "1pYCVpEZNuoqcLSpMKHTDdxQoRfm2hbiChbJAlg7u8Tc",
            // PARTIZAN 34: Guests, Invited and Otherwise
            "1h5kCZCIAs2HidKDfKl5alwrkVi4irPOf9d6n3Efq0sQ",
            "1iHISLaYMoCkF52VRJQIgdFoAEIoMG2uJRSn00Her1Yg",
            // PARTIZAN 37: The Gravity of Absence
            "1ppjhYxA-iqtj5XJldUAgtAXJey86w3X3Exw9ujNxGkU",
            "1S8OuCWM-AaUa10Ki4WhNTep-3jvrnoo07wb3U-ch0bA",
            "1_hy7-TCLd2xdlNIYE2o3RM-dAttQeRRkRE-jdFgj6wo",
            // PARTIZAN 41: Orbital Decay
            // PARTIZAN 42: The Unbreakable Quarry
            // PARTIZAN 43: The Tunnels Beneath
            // PARTIZAN 44: The Prison at the Top of the World
            // PARTIZAN 45: Operation Shackled Sun: Act 1: The Fray
            // PARTIZAN 46: Operation Shackled Sun: Act 2: The Gate
            // PARTIZAN 47: Operation Shackled Sun: Act 3: The Stories Told About the Things We Do
            "1K1_wER_xG8JHfEnbmpBWGi_XFDkg7HDVMquUT1RWzc8", // PARTIZAN 48: Post Mortem

        ]
    },
    {
        season_name: "sf",
        IDs: [
            "1TJpUD-ezhHZDmt6YPkqJ2GcA__TZf8UfJFVdQsSqOQc",
            "162v2Do07dxo9TxfI-bFzmN-XwBqvoYKTW11j7gzuWqU",
            // Sangfielle 02: The Curse of Eastern Folly Pt. 2
            "1zczvM89ydLIZxwTbihU9O041TBcaHWX-qsTnRF2CvcI",
            // Sangfielle 04: The Blackwick Group
            // Sangfielle 05: The Hymn of the Mother-Beast Pt. 1
            "1wShi5uvYHOEpNIZgF0JuNXLFEQmK6FGnG_kMFhVsZRA",
            "1Hy68u6GQNEMfAhPqATFcF4Dv5Jqk1Jd01b_XNO3SvDk", // Sangfielle 07: The Hymn of the Mother-Beast Pt. 3
            // Sangfielle 08: The Hymn of the Mother-Beast Pt. 4
            // Sangfielle 09: The Secret Ledger of Roseroot Hall Pt. 1
            // Sangfielle 10: The Secret Ledger of Roseroot Hall Pt. 2
            "1O7-ytnphNdpn5D1zLJXgviWrDUTYxs9PJoQd3xhPzCg", // Sangfielle 11: The Secret Ledger of Roseroot Hall Pt. 3
            "1G3OUBiskuTTkJYudxbCjuIFtyE5FOnngzi37vbizkFQ", // Sangfielle 12: The Secret Ledger of Roseroot Hall Pt. 4
            "1yoCrscfk-5k8IaM_6T0RXuXDBgyAJeBTcgY0ToeKE0w", // Sangfielle 13: Market Day in Blackwick
            "1H2HNCdwZw05GlvkgL9WVY7TkX4GplgACFTqSZyGF7-A", // Sangfielle 14: The Candle Factory Pt. 1
            "16p8PRryW2y1NuAFO4c_i86C_pmJgT6h7PM0z2oyctjc", // Sangfielle 15: The Candle Factory Pt. 2
            "1mYD3ArDEElAOm05NEU4FefmC7L7zCW1-vk64iK11Xf0", // Sangfielle 16: The Candle Factory Pt. 3
            "1YMfPjKnmsCyYjqXFJzw_ENyrLKTJkiKLiW89-4qA2UQ", // Sangfielle 17: What Happened at the Bell Metal Station Pt. 1
            "1aXI7ibRIZaI_eWsoxVxqUx1f3YjoGyVH2dcl2noAyv4", // Sangfielle 18: What Happened at Bell Metal Station Pt. 2
            "11neQMzcBxYBoQVA8X83QXVfdUfJBZEd6y9pOSPJUm4U", // Sangfielle 19: What Happened at Bell Metal Station Pt. 3
        ]
    }
];
let d = new Date();
logger.info("Current date: " + d);

async function getTranscripts(auth) {
    const drive = google.drive({version: 'v3', auth});
    for (const season of transcripts) {
        for (let i = 0; i < season.IDs.length; i++) {
            const dest = fs.createWriteStream("./transcripts/" + season.season_name + "/" + season.season_name + "_ep" + i + ".txt");
            const {data} = await drive.files.export(
                {
                    fileId: season.IDs[i],
                    mimeType: 'text/plain',
                },
                {
                    responseType: 'stream',
                }
            );

            data
                .on('end', function () {
                    logger.info("Finished downloading: " + season.season_name + "_ep" + i);
                    console.log("Finished downloading: " + season.season_name + "_ep" + i)
                })
                .on('error', function (err) {
                    logger.info('Error during download', err);
                })
                .pipe(dest);
        }
    }
}