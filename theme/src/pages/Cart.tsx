import { useState } from "react";
import { Link } from "react-router-dom";
import { ShoppingCart, Trash2, Plus, Minus, ArrowLeft, CreditCard, Shield, Zap } from "lucide-react";
import { Layout } from "@/components/layout/Layout";
import { SEOHead } from "@/components/SEOHead";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from "@/components/ui/card";

interface CartItem {
  id: string;
  name: string;
  price: number;
  period: string;
  quantity: number;
}

const Cart = () => {
  const [cartItems, setCartItems] = useState<CartItem[]>([]);

  const plans = [
    { id: "single", name: "Pro 1 Site", price: 49, period: "/year" },
    { id: "five", name: "Pro 5 Sites", price: 99, period: "/year" },
    { id: "unlimited", name: "Pro Unlimited", price: 199, period: "/year" },
    { id: "lifetime", name: "Pro Lifetime", price: 399, period: "one-time" },
  ];

  const addToCart = (plan: typeof plans[0]) => {
    setCartItems((prev) => {
      const existing = prev.find((item) => item.id === plan.id);
      if (existing) {
        return prev.map((item) =>
          item.id === plan.id ? { ...item, quantity: item.quantity + 1 } : item
        );
      }
      return [...prev, { ...plan, quantity: 1 }];
    });
  };

  const removeFromCart = (id: string) => {
    setCartItems((prev) => prev.filter((item) => item.id !== id));
  };

  const updateQuantity = (id: string, delta: number) => {
    setCartItems((prev) =>
      prev
        .map((item) =>
          item.id === id ? { ...item, quantity: Math.max(0, item.quantity + delta) } : item
        )
        .filter((item) => item.quantity > 0)
    );
  };

  const total = cartItems.reduce((sum, item) => sum + item.price * item.quantity, 0);

  return (
    <Layout>
      <SEOHead
        title="Shopping Cart – PDF Embed & SEO Optimize Pro"
        description="Review your PDF Embed Pro license selection and proceed to secure checkout."
        canonicalPath="/cart/"
      />

      <main className="pt-32 pb-20 min-h-screen bg-muted/30">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="max-w-5xl mx-auto">
            {/* Header */}
            <div className="flex items-center gap-4 mb-8">
              <Link
                to="/pro/"
                className="text-muted-foreground hover:text-primary transition-colors"
                title="Back to Pro features"
              >
                <ArrowLeft className="w-5 h-5" aria-hidden="true" />
                <span className="sr-only">Back to Pro</span>
              </Link>
              <h1 className="text-3xl md:text-4xl font-bold text-foreground flex items-center gap-3">
                <ShoppingCart className="w-8 h-8 text-primary" aria-hidden="true" />
                Shopping Cart
              </h1>
            </div>

            <div className="grid lg:grid-cols-3 gap-8">
              {/* Cart Items */}
              <div className="lg:col-span-2 space-y-4">
                {cartItems.length === 0 ? (
                  <Card className="text-center py-12">
                    <CardContent>
                      <ShoppingCart className="w-16 h-16 mx-auto text-muted-foreground/50 mb-4" aria-hidden="true" />
                      <h2 className="text-xl font-semibold text-foreground mb-2">Your cart is empty</h2>
                      <p className="text-muted-foreground mb-6">
                        Add a Pro license to get started with advanced PDF features.
                      </p>
                      <Button asChild className="gradient-hero shadow-glow">
                        <Link to="/pro/#pricing" title="View Pro pricing options">
                          <Zap className="w-4 h-4 mr-2" aria-hidden="true" />
                          View Pro Plans
                        </Link>
                      </Button>
                    </CardContent>
                  </Card>
                ) : (
                  cartItems.map((item) => (
                    <Card key={item.id} className="flex items-center p-4 gap-4">
                      <div className="w-12 h-12 rounded-lg gradient-hero flex items-center justify-center shrink-0">
                        <Zap className="w-6 h-6 text-primary-foreground" aria-hidden="true" />
                      </div>
                      <div className="flex-1">
                        <h3 className="font-semibold text-foreground">{item.name}</h3>
                        <p className="text-sm text-muted-foreground">
                          ${item.price} {item.period}
                        </p>
                      </div>
                      <div className="flex items-center gap-2">
                        <Button
                          variant="outline"
                          size="icon"
                          className="h-8 w-8"
                          onClick={() => updateQuantity(item.id, -1)}
                          aria-label={`Decrease quantity of ${item.name}`}
                        >
                          <Minus className="w-4 h-4" aria-hidden="true" />
                        </Button>
                        <span className="w-8 text-center font-medium">{item.quantity}</span>
                        <Button
                          variant="outline"
                          size="icon"
                          className="h-8 w-8"
                          onClick={() => updateQuantity(item.id, 1)}
                          aria-label={`Increase quantity of ${item.name}`}
                        >
                          <Plus className="w-4 h-4" aria-hidden="true" />
                        </Button>
                      </div>
                      <div className="text-right min-w-[80px]">
                        <p className="font-bold text-foreground">${item.price * item.quantity}</p>
                      </div>
                      <Button
                        variant="ghost"
                        size="icon"
                        className="text-destructive hover:text-destructive"
                        onClick={() => removeFromCart(item.id)}
                        aria-label={`Remove ${item.name} from cart`}
                      >
                        <Trash2 className="w-4 h-4" aria-hidden="true" />
                      </Button>
                    </Card>
                  ))
                )}

                {/* Quick Add Plans */}
                <Card className="mt-8">
                  <CardHeader>
                    <CardTitle className="text-lg">Quick Add License</CardTitle>
                  </CardHeader>
                  <CardContent>
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                      {plans.map((plan) => (
                        <Button
                          key={plan.id}
                          variant="outline"
                          className="flex flex-col h-auto py-3 hover:border-primary"
                          onClick={() => addToCart(plan)}
                          aria-label={`Add ${plan.name} to cart`}
                        >
                          <span className="text-xs text-muted-foreground">{plan.name}</span>
                          <span className="font-bold">${plan.price}</span>
                        </Button>
                      ))}
                    </div>
                  </CardContent>
                </Card>
              </div>

              {/* Order Summary */}
              <div className="lg:col-span-1">
                <Card className="sticky top-24">
                  <CardHeader>
                    <CardTitle>Order Summary</CardTitle>
                  </CardHeader>
                  <CardContent className="space-y-4">
                    {cartItems.length > 0 ? (
                      <>
                        {cartItems.map((item) => (
                          <div key={item.id} className="flex justify-between text-sm">
                            <span className="text-muted-foreground">
                              {item.name} × {item.quantity}
                            </span>
                            <span className="font-medium">${item.price * item.quantity}</span>
                          </div>
                        ))}
                        <div className="border-t pt-4">
                          <div className="flex justify-between text-lg font-bold">
                            <span>Total</span>
                            <span className="text-primary">${total}</span>
                          </div>
                        </div>
                      </>
                    ) : (
                      <p className="text-muted-foreground text-sm text-center py-4">
                        No items in cart
                      </p>
                    )}
                  </CardContent>
                  <CardFooter className="flex flex-col gap-3">
                    <Button
                      className="w-full gradient-hero shadow-glow"
                      size="lg"
                      disabled={cartItems.length === 0}
                      aria-label="Proceed to secure checkout"
                    >
                      <CreditCard className="w-4 h-4 mr-2" aria-hidden="true" />
                      Checkout
                    </Button>
                    <div className="flex items-center justify-center gap-2 text-xs text-muted-foreground">
                      <Shield className="w-3 h-3" aria-hidden="true" />
                      <span>Secure checkout powered by Stripe</span>
                    </div>
                  </CardFooter>
                </Card>

                {/* Guarantee */}
                <Card className="mt-4 bg-primary/5 border-primary/20">
                  <CardContent className="pt-4">
                    <p className="text-sm text-center">
                      <strong className="text-foreground">30-Day Money-Back Guarantee</strong>
                      <br />
                      <span className="text-muted-foreground">
                        Not satisfied? Get a full refund, no questions asked.
                      </span>
                    </p>
                  </CardContent>
                </Card>
              </div>
            </div>
          </div>
        </div>
      </main>
    </Layout>
  );
};

export default Cart;
