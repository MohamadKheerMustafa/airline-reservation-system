@component('mail::message')
<h1>{{$subject}}</h1>

<h1> {{$greeting}} </h1>

@component('mail::panel')

@foreach ($data as $item)
    <p> {{$item}} </p>
@endforeach
<div style={{
          background: "#202834",
          color: "#fff",
          width: "100%",
          minHeight: "100vh",
          padding:"20px 0"
        }}
      >
        <h1 style={{
            margin: "auto",
            textAlign: "center",
            padding: "15px 10px",
            borderRadius: "0 0 15px 15px",
            direction: "ltr",
            letterSpacing: "1px",
            backgroundColor: "#2c3646",
            width: "30%",
          }}>
          Ticket informations :
        </h1>
        <div
          style={{
            display: "flex",
            justifyContent: "space-around",
            alignItems: "center",
            width: "60%",
            margin: "20px auto",
            padding: "20px",
          }}
        >
          <h2 style={{ direction: "ltr" }}>
            seat number :{ {{$TicketInfo->seat_number}} }
          </h2>
          <h2 style={{ direction: "ltr" }}>
            status :{ {{$TicketInfo->status}} }
          </h2>
        </div>
        <div style={{
            backgroundColor: "#2c3646",
            width: "60%",
            margin: "10px auto",
            padding: "20px 30px",
            borderRadius: "20px",
          }}
        >
          <h2 style={{
              direction: "ltr",
              letterSpacing: "2px",
              margin: "auto",
              width: "fit-content",
            }}
          >
            flight info :
          </h2>
          <div
            style={{
              display: "flex",
              justifyContent: "space-around",
              alignItems: "center",
              margin: "20px 0",
            }}
          >
            <h3 style={{ direction: "ltr", letterSpacing: "1px" }}>
              flight_number:
              { {{$TicketInfo->flight->flight_number}} }
            </h3>
            <h3 style={{ direction: "ltr", letterSpacing: "1px" }}>
              airline Company:
              { {{$TicketInfo->flight->airline->name}} }
            </h3>
          </div>
          <div
            style={{
              margin: "20px 0",
            }}
          >
            <h3
              style={{
                textAlign: "center",
                direction: "ltr",
                letterSpacing: "1px",
                fontSize: "26px",
                position: "relative",
              }}
            >
              <span
                style={{
                  position: "absolute",
                  width: "15px",
                  height: "15px",
                  borderRadius: "50%",
                  backgroundColor: "#fff",
                  bottom: "-13px",
                  right: "368px",
                }}
              ></span>
              plane
              <span
                style={{
                  position: "absolute",
                  width: "12%",
                  height: "3px",
                  borderRadius: "50%",
                  backgroundColor: "#fff",
                  bottom: "-8px",
                  right: "330px",
                }}
              ></span>
            </h3>
            <div
              style={{
                display: "flex",
                justifyContent: "space-around",
                alignItems: "center",
                margin: "20px 0",
              }}
            >
              <h4
                style={{
                  direction: "ltr",
                  letterSpacing: "1px",
                  fontSize: "22px",
                }}
              >
                name :{ {{$TicketInfo->flight->plane->name}} }
              </h4>
              <h4
                style={{
                  direction: "ltr",
                  letterSpacing: "1px",
                  fontSize: "22px",
                }}
              >
                code :{ {{$TicketInfo->flight->plane->code}} }
              </h4>
            </div>
          </div>
          <div
            style={{
              margin: "20px 0",
            }}
          >
            <h3 style={{
               textAlign: "center",
               direction: "ltr",
               letterSpacing: "1px",
               fontSize: "26px",
               position: "relative",
             }}
           >
             <span
               style={{
                 position: "absolute",
                 width: "15px",
                 height: "15px",
                 borderRadius: "50%",
                 backgroundColor: "#fff",
                 bottom: "-13px",
                 right: "368px",
               }}
             ></span>
             origin
             <span
               style={{
                 position: "absolute",
                 width: "12%",
                 height: "3px",
                 borderRadius: "50%",
                 backgroundColor: "#fff",
                 bottom: "-8px",
                 right: "330px",
               }}
             ></span>
           </h3>
           <div
             style={{
               display: "flex",
               justifyContent: "space-around",
               alignItems: "center",
               margin: "20px 0",
             }}
           >
             <h4
               style={{
                 direction: "ltr",
                 letterSpacing: "1px",
                 fontSize: "22px",
               }}
             >
               City :{ {{$TicketInfo->flight->origin->name}} }
             </h4>
             <h4
               style={{
                 direction: "ltr",
                 letterSpacing: "1px",
                 fontSize: "22px",
               }}
             >
               Country:
               { {{$TicketInfo->flight->origin->city->name}} }
             </h4>
           </div>
         </div>
         <div
           style={{
             margin: "20px 0",
           }}
         >
           <h3
             style={{
               textAlign: "center",
               direction: "ltr",
               letterSpacing: "1px",
               fontSize: "26px",
               position: "relative",
             }}
           >
             <span
               style={{
                 position: "absolute",
                 width: "15px",
                 height: "15px",
                 borderRadius: "50%",
                 backgroundColor: "#fff",
                 bottom: "-13px",
                 right: "368px",
               }}
             ></span>
             Destination
             <span
               style={{
                 position: "absolute",
                 width: "12%",
                 height: "3px",
                 borderRadius: "50%",
                 backgroundColor: "#fff",
                 bottom: "-8px",
                 right: "330px",
               }}
             ></span>
           </h3>
           <div
             style={{
               display: "flex",
               justifyContent: "space-around",
               alignItems: "center",
               margin: "20px 0",
             }}
           >
             <h4
               style={{
                 direction: "ltr",
                 letterSpacing: "1px",
                 fontSize: "22px",
               }}
             >
               City:
                {{$TicketInfo->flight->destination->name}} 
             </h4>
             <h4
               style={{
                 direction: "ltr",
                 letterSpacing: "1px",
                 fontSize: "22px",
               }}
             >
               Country:
                {{$TicketInfo->flight->destination->city->name}}
             </h4>
           </div>
           <div
             style={{
               display: "flex",
               justifyContent: "space-around",
               alignItems: "center",
               margin: "20px 0",
             }}
           >
             <h4
               style={{
                 direction: "ltr",
                 letterSpacing: "1px",
                 fontSize: "22px",
                }}
              >
                departure Time:
                { {{$TicketInfo->flight->departure}} }
              </h4>
              <h4
                style={{
                  direction: "ltr",
                  letterSpacing: "1px",
                  fontSize: "22px",
                }}
              >
                arrival Time:
                { {{$TicketInfo->flight->arrival}} }
              </h4>
            </div>
            <div
              style={{
                display: "flex",
                justifyContent: "space-around",
                alignItems: "center",
                margin: "20px 0",
              }}
            >
              <h4
                style={{
                  direction: "ltr",
                  letterSpacing: "1px",
                  fontSize: "22px",
                }}
              >
                price :{ {{$TicketInfo->flight->price}} }
              </h4>
            </div>
          </div>
        </div>
      </div>
<p> {{ $footer }} </p>
@endcomponent